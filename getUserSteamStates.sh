#!/bin/bash
## Usage: getSteamUserStates
##
## Designed to be run at regular intervals (cron or equivalent)
##
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
php artisan steam:get-user-states