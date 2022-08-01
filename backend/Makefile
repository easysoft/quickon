###########################################
.EXPORT_ALL_VARIABLES:
VERSION_PKG := github.com/ergoapi/util/version
ROOT_DIR := $(CURDIR)
BUILD_DIR := $(ROOT_DIR)/_output
BIN_DIR := $(BUILD_DIR)/bin
GO111MODULE = on
GOPROXY = https://goproxy.cn,direct
GOPRIVATE = gitlab.zcorp.cc
GOSUMDB = sum.golang.google.cn

BUILD_RELEASE   ?= $(shell cat VERSION || echo "0.0.1")
BUILD_DATE := $(shell date "+%Y%m%d")
APP_VERSION := $(BUILD_RELEASE)-${BUILD_DATE}-$(GIT_COMMIT)

LDFLAGS := "-w \
	-X $(VERSION_PKG).release=$(APP_VERSION) \
	-X $(VERSION_PKG).gitVersion=$(APP_VERSION) \
	-X $(VERSION_PKG).gitCommit=$(GIT_COMMIT) \
	-X $(VERSION_PKG).gitBranch=$(GIT_BRANCH) \
	-X $(VERSION_PKG).buildDate=$(BUILD_DATE) \
	-X $(VERSION_PKG).gitTreeState=core \
	-X $(VERSION_PKG).gitMajor=1 \
	-X $(VERSION_PKG).gitMinor=0"

GO_BUILD_FLAGS+=-ldflags $(LDFLAGS)
GO_BUILD := go build $(GO_BUILD_FLAGS)

##########################################################################

help: ## this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

gencopyright: ## add copyright
	@bash hack/scripts/gencopyright.sh

fmt: ## fmt code
	gofmt -s -w .
	goimports -w -local gitlab.zcorp.cc/pangu/cne-api .
	@echo gofmt -l
	@OUTPUT=`gofmt -l . 2>&1`; \
	if [ "$$OUTPUT" ]; then \
		echo "gofmt must be run on the following files:"; \
        echo "$$OUTPUT"; \
        exit 1; \
    fi

lint: ## lint code
	@echo golangci-lint run --skip-files \".*test.go\" -v ./...
	@OUTPUT=`command -v golangci-lint >/dev/null 2>&1 && golangci-lint run --skip-files ".*test.go"  -v ./... 2>&1`; \
	if [ "$$OUTPUT" ]; then \
		echo "go lint errors:"; \
		echo "$$OUTPUT"; \
	fi

doc: ## doc
	hack/scripts/gendocs.sh

default: gencopyright doc fmt lint ## fmt code

build: ## build binary
	@echo "build bin ${GIT_VERSION} $(GIT_COMMIT) $(GIT_BRANCH) $(BUILD_DATE) $(GIT_TREE_STATE)"
	$(GO_BUILD) -o $(BIN_DIR)/cne-api cmd/main.go

run:
	go run cmd/main.go serve

clean: ## clean
	rm -rf $(BIN_DIR)

.PHONY : build clean
