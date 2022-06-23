#!/bin/bash
#
# Easysoft custom library

# shellcheck disable=SC1091

# Load Generic Libraries
. /opt/easysoft/scripts/liblog.sh

# Constants
BOLD='\033[1m'

# Functions

########################
# Print the welcome page
# Globals:
#   DISABLE_WELCOME_MESSAGE
#   EASYSOFT_APP_NAME
# Arguments:
#   None
# Returns:
#   None
#########################
print_welcome_page() {
    if [[ -z "${DISABLE_WELCOME_MESSAGE:-}" ]]; then
        if [[ -n "$EASYSOFT_APP_NAME" ]]; then
            print_image_welcome_page
        fi
    fi
}

########################
# Print the welcome page for a Easysoft Docker image
# Globals:
#   EASYSOFT_APP_NAME
# Arguments:
#   None
# Returns:
#   None
#########################
print_image_welcome_page() {
    local github_url="https://www.qucheng.com/saas.html"

    log ""
    log "${BOLD}Welcome to the Easysoft ${EASYSOFT_APP_NAME} container${RESET}"
    log "Subscribe to project updates by watching ${BOLD}${github_url}${RESET}"
    log "Submit issues and feature requests at ${BOLD}https://www.qucheng.com/page/support.html${RESET}"
    log ""
}
