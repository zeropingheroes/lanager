LANager
=======

LANager is a web application designed to make [LAN Parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Branch: `laravel-upgrade`

This unstable branch is where the work of upgrading to Laravel 5.6 is being done. Once complete this will be merged into the master branch.

## Installation

### Requirements
 - Ubuntu 18.04
 - PHP 7.1+
 - MySQL 5.7+
 - Nginx 1.14+

### Steps

- Install required packages:

    ```
    apt update && apt install php7.2 \
                               php7.2-mysql \
                               php7.2-mbstring \
                               php7.2-xml \
                               php7.2-fpm \
                               composer \
                               mysql-server \
    ```

- Clone a copy of LANager:

    `git clone -b laravel-upgrade https://github.com/zeropingheroes/lanager /var/www/lanager/`
    
- Install LANager's dependencies:

    `composer install --working-dir=/var/www/lanager`

- Create a Nginx site configuration:

    `nano /etc/nginx/sites-available/lanager`
    
    ```
    server {
            listen 80;
    
            root /var/www/lanager/public;
    
            index index.html index.htm index.php;
    
            server_name lanager.example.com;
    
            location / {
                try_files $uri $uri/ /index.php?$query_string;
            }
    
            location ~ \.php$ {
                    include snippets/fastcgi-php.conf;
    
                    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
            }
    }
    ```
- Enable the site:

    `ln -s /etc/nginx/sites-available/lanager /etc/nginx/sites-enabled/lanager`
    
- Reload Nginx:

    `systemctl reload nginx`
    
- Configure MySQL

    - *todo*

- Configure LANager
    
    `cd cd /var/www/lanager/ && cp .env.example .env && nano .env`
    
    - `STEAM_API_KEY` - Enter your [Steam API Key](http://steamcommunity.com/dev/apikey)
    - `APP_DEBUG` - Set to `false` once installed
    - `APP_URL` - Set to the full URL
    - *todo*


## Development

### Requirements

- [Vagrant](https://www.vagrantup.com/downloads.html)
- [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
- [Composer](https://getcomposer.org/)

### Installation:

1. `git clone -b laravel-upgrade https://github.com/zeropingheroes/lanager && cd lanager`
2. `cp .env.example .env`
3. `nano .env`
   1. `STEAM_API_KEY` - Enter your [Steam API Key](http://steamcommunity.com/dev/apikey)
4. `composer install`
5. `vagrant up`

Your development environment will now be available available at [lanager.localhost:8000](http://lanager.localhost:8000/)

## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
	* [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see
	* Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues)
	* Or if you're really hardcore, request commit access

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)
