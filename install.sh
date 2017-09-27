#!/bin/bash
# Exit on the first error
set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
GREEN='\033[0;32m'
BLACK='\033[0m'

printf "${GREEN}Installing dependencies with apt${BLACK}\n"
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt update -y && apt upgrade -y
apt install -y git php5-cli php5-common php5-mcrypt php5-curl php5-mysql libapache2-mod-php5 apache2 mysql-client-5.6 mysql-server-5.6

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
chmod -R 777 /vagrant/app/storageprintf "${GREEN}Creating MySQL database and user${BLACK}\n"
mysql -u root -proot -e "CREATE DATABASE lanager;"
mysql -u root -proot -e "CREATE USER 'lanager'@'localhost' IDENTIFIED BY 'lanager';"
mysql -u root -proot -e "GRANT ALL PRIVILEGES ON lanager.* TO 'lanager'@'localhost';"
mysql -u root -proot -e "FLUSH PRIVILEGES;"