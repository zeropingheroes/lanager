#!/usr/bin/env bash

# Abort on any error
set -e

# Wait for db connection to be up before continuing
/var/www/docker-wait-for.sh "db:3306"

FILE="/var/www/storage/.install-completed"

# Check if installation has been completed
if ! [[ -f $FILE ]]; then

    php artisan migrate:fresh --force
    php artisan db:seed --force --class=Database\\Seeders\\DatabaseSeeder
    php artisan lanager:import-steam-apps-csv

    touch $FILE

fi

php-fpm
