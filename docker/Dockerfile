FROM hub.qucheng.com/library/php:7.4.28-fpm-alpine3.15 

LABEL maintainer "zhouyueqiu <zhouyueqiu@easycorp.ltd>"

ENV OS_ARCH="amd64" \
    OS_NAME="alpine-3.15"
COPY docker/alpine/prebuildfs /

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tencent.com/g' /etc/apk/repositories \
    && install_packages tzdata gettext unzip s6 su-exec bash pwgen nginx \
    && rm /bin/sh \
    && ln -s /bin/bash /bin/sh \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shanghai" > /etc/timezone \
    && apk del tzdata \
    && rm -rf /var/cache/apk/* /etc/nginx/http.d/* /usr/local/etc/php-fpm.d/*

ENV EASYSOFT_APP_NAME="QuCheng 1.0 beta2"

# Install php core-exts
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "php-exts" "7.4.28" -c 2d85e4d24f5341b697881275ef0407f62815ed4f430087d2b091c4448b9da0a1

# Install mysql client
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "mysql-client" "10.6.7" -c e6861a24f98a241d0982080e4f65f202bf1b59ca7955cc683cd0c5a52f2970cf

# Install wait-for-port cmd
RUN . /opt/easysoft/scripts/libcomponent.sh && component_unpack "wait-for-port" "1.01" -c a1000444750bb68e05db7bdf6ab87682a413ad989d418557542ef2586e9ec63a

COPY --chown=82 docker/alpine/rootfs /

# Copy qucheng source code
WORKDIR /apps/qucheng
COPY --chown=82 . .

RUN rm -rf /apps/qucheng/docker \
    && chmod +x /usr/bin/entrypoint.sh \
    && chmod 777 /apps/qucheng/tmp

EXPOSE 80

VOLUME ["/data"]

ENTRYPOINT ["/usr/bin/entrypoint.sh"]