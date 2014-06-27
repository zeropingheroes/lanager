#!/bin/bash
## Usage: steam:import-user-states
##
## Designed to be run at regular intervals (cron or equivalent)
##
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
php artisan steam:import-user-states
