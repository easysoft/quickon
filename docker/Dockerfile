# Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
# Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
# license that can be found in the LICENSE file.

# Build qucheng image
FROM hub.qucheng.com/library/debian:11.5-slim

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

# Install php-mcrypt
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "php-mcrypt" "1.0.6" -c 6f5be2c44d0dd969af38db9318dfb70396ed222686207d31d7ae386ad846d0f6 \
    && phpenmod mcrypt

# Install apache
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "apache" "2.4.53-02" -c 0df3a5e53ef078b75dd43facd6e1473d9ff3c4c5e5b865c4dc066e4e2ece6b07

# Install su-exec
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "su-exec" "0.2" -c 687d29fd97482f493efec73a9103da232ef093b2936a341d85969bc9b9498910

# Install render-template
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "render-template" "1.0.1-10" -c 5e410e55497aa79a6a0c5408b69ad4247d31098bdb0853449f96197180ed65a4

# Install mysql-client
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "mysql-client" "10.5.15-20220817" -c c4f82cb5b66724dd608f0bafaac400fc0d15528599e8b42be5afe8cedfd16488

# Install mongodb
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "mongodb" "1.14.1-202210181821" -c 98cf5863978893c5c193868acc5eafbb60ad6f0cc9425082ca800414f26933cc

# Install postgresql
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "postgresql" "7.4-202210181821" -c 1f909dba9ec152cacdaa5fe5c045bfec3a0eb6e67dbdabaf857f97938ea20ab5

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

ARG EDITION
ENV QUICKON_EDITION=$EDITION

# Copy qucheng source code
WORKDIR /apps/qucheng
COPY --chown=www-data frontend .

# Copy qucheng backend
ADD backend/_output/bin/cne-api /apps/backend/bin/cne-api

# Copy apache,php and qucheng config files
COPY docker/rootfs /

RUN chmod +x /usr/bin/entrypoint.sh /apps/backend/bin/cne-api \
    && chmod 777 /apps/qucheng/tmp

EXPOSE 80

VOLUME ["/data", "/logs"]

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
