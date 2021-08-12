#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

cd /home/vagrant/lanager/

sudo update-alternatives --set php /usr/bin/php7.4
sudo update-alternatives --set php-config /usr/bin/php-config7.4

rm -rf vendor

composer install

php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan storage:link
php artisan lanager:import-steam-apps-csv
php artisan db:seed --class=TestDataSeeder
