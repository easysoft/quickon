date_time := $(shell date +%Y%m%d)
ci_tag := $(citag)
export host_name=$(shell hostname)
export commit_id := $(shell git rev-parse --short HEAD)
export branch_name := $(shell git branch -r --contains | head -1 | sed -E -e "s%(HEAD ->|origin|upstream)/?%%g" | xargs | tr '/' '-' )
export _branch_prefix := $(shell echo $(branch_name) | sed 's/-.*//')

ifneq (,$(filter $(_branch_prefix), test sprint))
  export TAG=$(branch_name)
  export BUILD_VERSION=$(branch_name)-$(date_time)-$(commit_id)
else
  ifdef ci_tag
    export TAG=$(ci_tag)
    export BUILD_VERSION=$(ci_tag)-$(date_time)-$(commit_id)
  else
    export TAG=$(branch_name)-$(date_time)-$(commit_id)
    export BUILD_VERSION=$(TAG)
	endif
endif

help: ## this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build-qucheng: ## 构建镜像
	docker build --build-arg VERSION=$(BUILD_VERSION) \
				--build-arg GIT_COMMIT=$(commit_id) \
				--build-arg GIT_BRANCH=$(branch_name) \
				-t hub.qucheng.com/platform/qucheng:$(TAG) \
				-f docker/Dockerfile .

build-api: ## 构建api程序
	docker build --build-arg GIT_COMMIT=$(commit_id) \
				--build-arg GIT_BRANCH=$(branch_name) \
				-t hub.qucheng.com/platform/cne-api:$(TAG) \
				-f docker/Dockerfile.api .

build-all: build-api build-qucheng # 构建所有镜像

push-qucheng: ## push qucheng 镜像
	docker push hub.qucheng.com/platform/qucheng:$(TAG)

push-api: ## push api镜像
	docker push hub.qucheng.com/platform/cne-api:$(TAG)

push-all: push-qucheng push-api ## push 所有镜像

qucheng: build-qucheng push-qucheng ## qucheng构建并推送

api: build-api push-api ## api构建并推送

run: ## 运行
	docker-compose -f docker-compose.yml up -d mysql qucheng

run-dev: pull kubeconfig ## 运行开发环境
	chown 33:33 . -R
	docker-compose -f docker-compose.yml up -d mysql qucheng-dev

ps: run ## 运行状态
	docker-compose -f docker-compose.yml ps

stop: ## 停服务
	docker-compose -f docker-compose.yml stop

restart: build clean ps ## 重构

clean: stop ## 停服务
	docker-compose -f docker-compose.yml down
	docker volume prune -f

logs: ## 查看运行日志
	docker-compose -f docker-compose.yml logs

pull: ## 拉取最新镜像
	docker-compose -f docker-compose.yml pull

kubeconfig:
	sed -r -e "s%(\s+server:\s+https://).*(:6443)%\1$(host_name)\2%" ~/.kube/config > /root/.kube/config.outside
