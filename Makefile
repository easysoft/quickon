export TAG := $(shell grep ver VERSION | cut -d '=' -f 2)

help: ## this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## 构建镜像
	docker build --build-arg VERSION=$(TAG) -t hub.qucheng.com/platform/qucheng:$(TAG) -f docker/Dockerfile .

push: ## push 镜像
	docker push hub.qucheng.com/platform/qucheng:$(TAG)

run: ## 运行
	docker-compose -f docker-compose.yml up -d

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
