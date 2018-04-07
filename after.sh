#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

cd /home/vagrant/lanager/ && php artisan migrate:fresh && php artisan db:seed
