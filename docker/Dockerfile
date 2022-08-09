# Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
# Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
# license that can be found in the LICENSE file.

FROM hub.qucheng.com/library/god AS builder

RUN sed -i -r 's/(deb|security).debian.org/mirrors.tencent.com/g' /etc/apt/sources.list \
    && apt-get update \
    && apt-get install upx -y

ENV WORKDIR /go/src/gitlab.zcorp.cc/pangu/cne-api
WORKDIR $WORKDIR

ENV GOPROXY=https://goproxy.cn,direct

COPY backend/go.mod go.mod

COPY backend/go.sum go.sum

RUN go mod download

COPY VERSION .

COPY backend .

ARG GIT_COMMIT
ARG GIT_BRANCH

RUN make build && cp ${WORKDIR}/_output/bin/cne-api /usr/bin/cne-api && upx -9 /usr/bin/cne-api

# Build qucheng image
FROM hub.qucheng.com/library/debian:11.3-slim

LABEL maintainer "zhouyueqiu <zhouyueqiu@easycorp.ltd>"

ENV OS_ARCH="amd64" \
    OS_NAME="debian-11" \
    HOME_PAGE="www.qucheng.com"

COPY docker/prebuildfs /

ENV TZ=Asia/Shanghai \
    DEBIAN_FRONTEND=noninteractive

RUN sed -i -r 's/(deb|security).debian.org/mirrors.cloud.tencent.com/g' /etc/apt/sources.list \
    && install_packages curl wget tzdata zip unzip s6 pwgen cron apt-transport-https ca-certificates procps \
    && ln -fs /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone \
    && dpkg-reconfigure --frontend noninteractive tzdata

# Install internal php
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "php" "7.4.28" -c 934dd0320ee217465f6a8496b0858d120c3fd45b413f1c9ff833731a848cefa7

# Install php-ext-ioncube
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "php-ext-ioncube" "11.0.1" -c 9a6ee08aa864f2b937b9a108d3ec8679ae3a5f08f92a36caf5280520432315ad

# Install apache
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "apache" "2.4.53-02" -c 0df3a5e53ef078b75dd43facd6e1473d9ff3c4c5e5b865c4dc066e4e2ece6b07

# Install su-exec
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "su-exec" "0.2" --checksum 687d29fd97482f493efec73a9103da232ef093b2936a341d85969bc9b9498910

# Install render-template
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "render-template" "1.0.1-10" --checksum 5e410e55497aa79a6a0c5408b69ad4247d31098bdb0853449f96197180ed65a4

# Install mysql-client
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "mysql-client" "10.5.15" -c 31182985daa1a2a959b5197b570961cdaacf3d4e58e59a192c610f8c8f1968a8

# Install wait-for-port
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "wait-for-port" "1.01" -c 2ad97310f0ecfbfac13480cabf3691238fdb3759289380262eb95f8660ebb8d1

# Install toolkit
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "toolkit" "20220630" -c cc920426e0c72e9bb6833807e0dddf9f27177bb2ed28f698c1e05eddee9c1069

# Clear apahce vhost config
RUN rm -rf /etc/apache2/sites-available/* /etc/apache2/sites-enabled/*

# qucheng envs
ARG VERSION
ENV BUILD_VERSION=$VERSION
ENV EASYSOFT_APP_NAME="QuCheng $BUILD_VERSION"

# Copy qucheng source code
WORKDIR /apps/qucheng
COPY --chown=www-data frontend .

# Copy qucheng backend
COPY --from=builder /usr/bin/cne-api /apps/backend/bin/cne-api

# Copy apache,php and qucheng config files
COPY docker/rootfs /

RUN chmod +x /usr/bin/entrypoint.sh /apps/backend/bin/cne-api \
    && chmod 777 /apps/qucheng/tmp

EXPOSE 80

VOLUME ["/data"]

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
