date_time := $(shell date +%Y%m%d)

ci_tag := $(or $(citag),$(TAG_NAME),$(CI_COMMIT_TAG))

ifdef JENKINS_HOME
	export commit_id := $(shell echo $${GIT_COMMIT:0:7})
	export branch_name := $(BRANCH_NAME)
else
	export commit_id := $(shell git rev-parse --short HEAD)
	export branch_name := $(shell git branch --show-current)
	export kube_api_host := $(shell kubectl get svc kubernetes -n default -o jsonpath='{.spec.clusterIP}')
	export app_domain := $(shell helm get values -n cne-system qucheng -o json | jq -r '.env.APP_DOMAIN')
endif

# export kube_api_host := $(shell kubectl get svc kubernetes -n default -o jsonpath='{.spec.clusterIP}')

export branch_name_valid := $(shell echo $(branch_name) | tr "/" "-")
export _branch_prefix := $(shell echo $(branch_name_valid) | sed 's/-.*//')

ifneq (,$(filter $(_branch_prefix), test sprint))
  export TAG=$(branch_name_valid)
  export BUILD_VERSION=$(branch_name_valid)-$(date_time)-$(commit_id)
else
  ifdef ci_tag
    export TAG=$(ci_tag)
    export BUILD_VERSION=$(ci_tag)-$(date_time)-$(commit_id)
  else
    export TAG=$(branch_name_valid)-$(date_time)-$(commit_id)
    export BUILD_VERSION=$(TAG)
	endif
endif

help: ## this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

dep-backend:
	cd backend && go mod download

build-backend:
	cd backend && CGO_ENABLED=0 make build

test-backend:
	cd backend && CGO_ENABLED=0 go test ./...

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

run-dev: pull mountFiles ## 运行开发环境
	$(shell if [ -f ../qucheng-dev ]; then mkdir ../qucheng-dev; fi;)
	cp -r ./frontend ../qucheng-dev  >> /dev/null  2>&1 & 
	chown -R 33:33 ../qucheng-dev -R
	docker-compose -f docker-compose.yml up -d mysql qucheng-dev

run-frontend-dev: pull ## 运行开发环境-仅启动frontend
	$(shell if [ -f ../qucheng-dev ]; then mkdir ../qucheng-dev; fi;)
	syncext.sh ./frontend ../qucheng-dev  >> /dev/null  2>&1 & 
	syncext.sh ../quickonext/quickonbiz ../qucheng-dev  >> /dev/null  2>&1 & 
	chown -R 33:33 ../qucheng-dev -R
	docker-compose -f docker-compose.yml up -d mysql qucheng-dev
run-biz-dev: pull mountFiles ## 运行企业版开发环境
	$(shell if [ -f ../qucheng-dev ]; then mkdir ../qucheng-dev; fi;)
	syncext.sh ./frontend ../qucheng-dev  >> /dev/null  2>&1 & 
	syncext.sh ../quickonext/quickonbiz ../qucheng-dev  >> /dev/null  2>&1 & 
	chown -R 33:33 ../qucheng-dev -R
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

mountFiles:
	mkdir -p /root/.config/helm
	@kubectl get cm -n cne-system qucheng-files -o jsonpath='{.data.repositories\.yaml}' > /root/.config/helm/repositories.yaml.dev
	@sed -r -e "s%(\s+server:\s+https://).*%\1$(kube_api_host):443%" ~/.kube/config > /root/.kube/config.dev

debug:
	@echo $(branch_name) "123"

