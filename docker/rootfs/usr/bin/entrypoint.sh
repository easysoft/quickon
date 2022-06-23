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

# Load envs
. /etc/s6/s6-init/envs

print_welcome_page

# Enable backend service
if [ "$ENABLE_BACKEND" == "true" ];then
    make_soft_link "/etc/s6/s6-available/backend" "/etc/s6/s6-enable/01-backend" "www-data"
fi
# Enable apache
make_soft_link "/etc/s6/s6-available/apache" "/etc/s6/s6-enable/02-apache" "www-data"

# Prepare cron
if [ "$ENABLE_CRON" == "true" ];then
    make_soft_link "/etc/s6/s6-available/cron " "/etc/s6/s6-enable/03-cron" "www-data"
fi

# Start s6 or other custom cmd
if [ $# -gt 0 ]; then
    exec "$@"
else
    # Init database,persistence,php and apache
    /etc/s6/s6-init/run

    # Run services
    exec /usr/bin/s6-svscan /etc/s6/s6-enable
fi