#!/bin/bash
#
# Easysoft mysql server handler library

# shellcheck disable=SC1090,SC1091

# Load generic libraries
. /opt/easysoft/scripts/liblog.sh
. /opt/easysoft/scripts/libos.sh

########################
# Check and waiting MySQL to be ready. 
# Globals:
#   MAXWAIT
#   MYSQL_HOST
#   MYSQL_PORT
# Arguments:
#   $1 - mysql service host
#   $2 - mysql service port
# Returns:
#   0 if the mysql server is can be connected, 1 otherwise
#########################
wait_for_mysql() {
    local retries=${MAXWAIT:-30}
    local mysql_host="${1:-$MYSQL_HOST}"
    local mysql_port="${2:-$MYSQL_PORT}"
    info "Check whether the MySQL is available."

    for ((i = 1; i <= retries; i += 1)); do
        if wait-for-port --host="${mysql_host}" --state=inuse --timeout=1 "${mysql_port}" > /dev/null 2>&1;
        then
            info "MySQL is ready."
            break
        fi

        warn "Waiting MySQL $i seconds"

        if [ "$i" == "$retries" ]; then
            error "Unable to connect to MySQL: $mysql_host:$mysql_port"
            return 1
        fi
    done
    return 0
}

########################
# Initialize app database.
# Globals:
#   MYSQL_HOST
#   MYSQL_PORT
#   MYSQL_USER
#   MYSQL_PASSWORD
#   MYSQL_DB
# Arguments:
#   $1 - mysql service host
#   $2 - mysql service port
#   $3 - app database name
# Returns:
#   0 if the database create succeed,1 otherwise
#########################
mysql_init_db() {
    local init_db=${1:-MYSQL_DB}
    local -a args=("--host=$MYSQL_HOST" "--port=$MYSQL_PORT" "--user=$MYSQL_USER" "--password=$MYSQL_PASSWORD")
    local command="/usr/bin/mysql"

    args+=("--execute=CREATE DATABASE IF NOT EXISTS $init_db;")
    
    info "Check $EASYSOFT_APP_NAME database."
    debug_execute "$command" "${args[@]}" || return 1
}

########################
# Import to mysql from mysql dump file
# Globals:
#   MYSQL_HOST
#   MYSQL_PORT
#   MYSQL_USER
#   MYSQL_PASSWORD
#   MYSQL_DB
# Arguments:
#   $1 - app database name
#   $2 - mysql dump file(*.sql) 
# Returns:
#   0 if import succeed,1 otherwise
#########################
mysql_import_to_db() {
    local db_name=${1:?missing db name}
    local sql_file=${2:?missing db init file}
    local -a args=("--host=$MYSQL_HOST" "--port=$MYSQL_PORT" "--user=$MYSQL_USER" "--password=$MYSQL_PASSWORD" "$db_name")
    local command="/usr/bin/mysql"

    if [ -f "$sql_file" ] ;then
        info "Import $sql_file to ${db_name}."
        debug_execute "$command" "${args[@]}" < "$sql_file" || return 1
    else
        error "The specified import file: $sql_file does not exist"
        return 1
    fi 

}