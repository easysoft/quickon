# Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
# Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
# license that can be found in the LICENSE file.

# Compile the backend API service
FROM hub.qucheng.com/library/god AS builder

RUN sed -i -r 's/(deb|security).debian.org/mirrors.tencent.com/g' /etc/apt/sources.list \
    && apt-get update \
    && apt-get install upx -y

WORKDIR /go/src

ENV GOPROXY=https://goproxy.cn,direct

COPY backend/go.mod go.mod

COPY backend/go.sum go.sum

RUN go mod download

COPY VERSION .

COPY backend .

ARG GIT_COMMIT
ARG GIT_BRANCH

RUN make build && upx -9 /go/src/_output/bin/cne-api
