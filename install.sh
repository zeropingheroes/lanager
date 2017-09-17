#!/bin/bash
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
GREEN='\033[0;32m'
BLACK='\033[0m'

printf "${GREEN}Installing dependencies with apt${BLACK}\n"
apt update -y
apt upgrade -y
apt install -y git
apt install -y php5-cli
apt install -y php5-common
apt install -y php5-mcrypt
apt install -y php5-curl
apt install -y php5-mysql
apt install -y libapache2-mod-php5
apt install -y apache2

printf "${GREEN}Enabling PHP extensions${BLACK}\n"
php5enmod mcrypt curl mysql

printf "${GREEN}Installing Composer${BLACK}\n"
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

printf "${GREEN}Installing dependencies with Composer${BLACK}\n"
cd $SCRIPT_DIR && composer install

printf "${GREEN}Linking site's public directory to Apache's webroot${BLACK}\n"
rm -R /var/www/html
ln -s /vagrant/public /var/www/html

printf "${GREEN}Setting permissions for app \"storage\" directory${BLACK}\n"
chmod -R 777 /vagrant/app/storage