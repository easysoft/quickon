[www]
user = www-data
group = www-data
listen = /run/php-fpm.sock
listen.owner = www-data
listen.group= www-data
listen.mode = 0660

pm = dynamic
pm.max_children = $PM_MAX_CHILDREN
pm.start_servers = $PM_START_NUM
pm.min_spare_servers = $PM_MIN_SPARE_NUM
pm.max_spare_servers = $PM_MAX_SPARE_NUM
pm.max_requests = $PM_MAX_REQUESTS

; set env to php
env[MYSQL_HOST] = $MYSQL_HOST
env[MYSQL_PORT] = $MYSQL_PORT
env[MYSQL_DB] = $MYSQL_DB
env[MYSQL_USER] = $MYSQL_USER
env[MYSQL_PASSWORD] = $MYSQL_PASSWORD
