#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

cd /home/vagrant/lanager/

composer install

php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan storage:link
php artisan lanager:import-steam-apps-csv
php artisan lanager:update-steam-apps
php artisan lanager:update-steam-apps-metadata
php artisan lanager:update-origin-games
