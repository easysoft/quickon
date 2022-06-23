#!/bin/bash

# shellcheck disable=SC1091

set -o errexit
set -o nounset
set -o pipefail

[ -n "${DEBUG:+1}" ] && set -x

# Load libraries
. /opt/easysoft/scripts/liblog.sh
. /opt/easysoft/scripts/libfs.sh
. /opt/easysoft/scripts/libeasysoft.sh

print_welcome_page

# Default disable cron
ENABLE_CRON=${ENABLE_CRON:-false}

# Enable php-fpm
make_soft_link "/etc/s6/s6-available/php-fpm" "/etc/s6/s6-enable/01-php-fpm" "www-data"

# Enable nginx
make_soft_link "/etc/s6/s6-available/nginx" "/etc/s6/s6-enable/02-nginx" "www-data"

# Prepare cron
if [ "$ENABLE_CRON" == "true" ];then
    make_soft_link "/etc/s6/s6-available/cron " "/etc/s6/s6-enable/03-cron" "www-data"
fi

# Start s6 or other custom cmd
if [ $# -gt 0 ]; then
    exec "$@"
else
    # Init database,persistence,php-fpm and nginx
    /etc/s6/s6-init/run

    # Run php-fpm and nginx
    exec /bin/s6-svscan /etc/s6/s6-enable
fi