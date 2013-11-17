# -*- mode: ruby -*-
# vi: set ft=ruby :

# Get Vagrant from http://downloads.vagrantup.com/
$script = <<SCRIPT
if [ ! -f "$HOME/.provisioned" ]; then

# Set up the server

## Install dependencies (PHP, Apache, MySQL)
sudo DEBIAN_FRONTEND=noninteractive apt-get -q -y update
sudo DEBIAN_FRONTEND=noninteractive apt-get -q -y upgrade
sudo DEBIAN_FRONTEND=noninteractive apt-get -o dir::cache::archives="/vagrantcache/apt" -o Dpkg::Options::='--force-confnew' -f -q -y install \
  libapache2-mod-php5 php-pear php5-cli php5-mysql php5-mcrypt mysql-server curl git vim

## Configure MySQL
echo "CREATE DATABASE lanager;" | mysql
echo "CREATE USER 'lanager'@'localhost' IDENTIFIED BY 'lanager';" | mysql
echo "GRANT ALL PRIVILEGES ON  lanager.* TO  'lanager'@'localhost' WITH GRANT OPTION;" | mysql
echo "CREATE USER 'lanager'@'%' IDENTIFIED BY 'lanager';" | mysql
echo "GRANT ALL PRIVILEGES ON  lanager.* TO  'lanager'@'%' WITH GRANT OPTION;" | mysql
mysqladmin -u root password vagrant

# Bind MySQL to all of the box's NICs to enable external access
sed -i 's/bind-address/;bind-address/g' /etc/mysql/my.cnf 
sudo service mysql restart

## Configure Apache
sudo a2enmod rewrite

echo "<VirtualHost *:80>
ServerName lanager.dev
DocumentRoot /vagrant/public
<Directory />
  Options FollowSymLinks
  AllowOverride None
</Directory>
<Directory /vagrant/public/>
  Options Indexes FollowSymLinks MultiViews
  AllowOverride All
  Order allow,deny
  allow from all
</Directory>
ErrorLog /var/log/apache2/lanager.dev/error.log
CustomLog /var/log/apache2/lanager.dev/access.log combined
LogLevel info
</VirtualHost>" | sudo tee /etc/apache2/sites-available/lanager.dev > /dev/null

sudo mkdir -p /var/log/apache2/lanager.dev/
sudo touch /var/log/apache2/lanager.dev/{error,access}.log
sudo chown -R www-data:www-data /var/log/apache2/lanager.dev
sudo a2ensite lanager.dev
sudo a2dissite default
sudo service apache2 restart

echo ">>>> Installing composer"
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

echo ">>>> Creating Vagrant Symlink"
ln -s /vagrant /home/vagrant/lanager

echo ">>>> Creating autoloader"
cd /vagrant/
composer dump-autoload
php artisan dump-autoload

echo ">>>> Creating and filling database schema"
php artisan migrate --package=zeropingheroes/lanager-core
php artisan db:seed --class=LanagerSeeder

echo ">>>> Publishing package assets to public directory"
php artisan asset:publish zeropingheroes/lanager-core

# Mark box as provisioned
touch $HOME/.provisioned
else
  echo "Already provisioned."
fi

echo
echo "######################"
echo "# START READING HERE #"
echo "######################"
echo
echo "Ensure that you have configured lanager.dev to point to 127.0.0.1"
echo "in your hosts file, and you should be able to access the VM from"
echo "http://lanager.dev:8080"
echo
echo "The DSN is mysql://lanager:lanager@lanager.dev:3307/lanager".
echo
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.guest = :linux
  #config.vm.hostname = "lanager.dev"
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  
  # Forward ports for web (8080) and MySQL (3307)
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 3306, host: 3307
  
  # Run script defined at top of this file
  config.vm.provision :shell, :inline => $script
  
  # Sync vagrantcache directory
  # used primarily for apt installs and upgrades,
  # useful for working on the vagrant box itself
  config.vm.synced_folder ".vagrantcache/", "/vagrantcache/"
end