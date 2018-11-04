LANager
=======

LANager is a web application designed to make [LAN Parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Features

### Encourage Socialising & Participation

* **Games being played** - by your LAN's attendees, updated every minute from Steam
* **Events timetable** - so your attendees know what's on at the LAN
* **Achievements** - created and awarded to attendees by you
* **Attendee profiles** - with a link to their Steam profile, and their gameplay history at the LAN
* **Games in common** - when viewing another attendee's profile
* **Games recently played** - showing the top games most recently played by attendees at the LAN

### Broadcast Useful Information 
* **Live Dashboard** - showing games being played by attendees, current and upcoming events, for TVs/projectors around your venue 
* **Guides** - written by you, to help attendees learn about, e.g. venue facilities, rules or gameplay guides
* **Links** - to other websites, e.g. your organisation's website, or a game statistics page

## Requirements

* Server running Ubuntu Server 18.04 (_with shell access - basic web hosting is not supported_)
* [Steam API Key](http://steamcommunity.com/dev/apikey)
* Internet access

While it's possible to run LANager on a server at your venue, only accessible internally, we recommend you cloud host, so outside of
your events you can easily update the site and prepare for your next LAN, and your attendees can find out about it ahead of the event.

## Installation

- Enable the `universe` package repository:

    ```
    sudo add-apt-repository universe
    ```

- Install required packages:

    ```
    sudo apt update
    sudo apt install php7.2-common php7.2-fpm php7.2-mysql php7.2-mbstring php7.2-bcmath php7.2-xml php7.2-zip
    sudo apt install zip composer mysql-server nginx
    ```

- Create a Nginx site configuration:

    ```
    sudo nano /etc/nginx/sites-available/lanager
    ```

    ```
    server {
            listen 80;
    
            root /var/www/lanager/public;
    
            index index.html index.htm index.php;
    
            # Change to your domain:
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

    ```
    ln -s /etc/nginx/sites-available/lanager /etc/nginx/sites-enabled/lanager
    ```

- Disable the default site:

    ```
    rm /etc/nginx/sites-enabled/default
    ```

- Reload Nginx:

    ```
    systemctl reload nginx
    ```
    
- Configure MySQL:

    ```
    mysql
    ```
    
    ```
    CREATE DATABASE lanager;
    CREATE USER 'lanager'@'%' IDENTIFIED BY 'YOUR-PASSWORD-HERE';
    GRANT ALL PRIVILEGES ON lanager.* TO 'lanager'@'%';
    FLUSH PRIVILEGES;
    QUIT;
    ```

- Clone a copy of LANager:

    ```
    git clone -b laravel-upgrade https://github.com/zeropingheroes/lanager /var/www/lanager/
    ``` 

- Grant permissions:

    ```
    chgrp www-data -R /var/www/lanager/
    chmod 777 -R /var/www/lanager/storage
    ```

- Install LANager's dependencies:

    ```
    composer install --working-dir=/var/www/lanager
    ```

- Configure LANager:
    
    ```
    cd /var/www/lanager/ && cp .env.example .env && nano .env
    ```
    
    - `APP_URL` - The full URL
    - `APP_TIMEZONE` - Your [timezone](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones#List)
    - `STEAM_API_KEY` - Your [Steam API Key](http://steamcommunity.com/dev/apikey)
    - `DB_PASSWORD` - The password you chose for the `lanager` MySQL user above

- Run first-time setup commands:

    ```
    php artisan key:generate
    php artisan migrate:fresh
    php artisan db:seed
    php artisan storage:link
    php artisan lanager:update-steam-apps
    ```

- Visit the app URL to check that the installation was successful

- Enable the scheduled commands:

    ```
    crontab -e
    ```

    ```
    * * * * * php /var/www/lanager/artisan schedule:run >> /dev/null 2>&1
    ```

- Disable debugging, set the site's environment to *production*, and enable MySQL logging:

    `nano /var/www/lanager/.env`

    - `APP_DEBUG=false`
    - `APP_ENV=production`
    - `LOG_CHANNEL=stack`

## Troubleshooting

- Enable debugging in your `.env` file

- [Create an issue](https://github.com/zeropingheroes/lanager/issues) with the error message(s) you see

- If you don't see an error message, in your `.env` file:
    - set `LOG_CHANNEL=stack` 
    - check for errors in `/var/www/lanager/storage/logs/laravel.log`
    
## Getting Started

### Become a Super Admin

The first user to log in to LANager is assigned the "Super Admin" role, so ensure you log in once installation has completed.
Super Admins can perform any action on the site, including assigning roles to other users. 

### Create Your LAN Event

Before you can start creating an event schedule, publishing any guides or awarding achievements, you'll need to create a LAN, which
represents your whole event, whether it's a single-day or all week long.

Once logged in, click ⚙ > LANs, and then click the + button to go to the LAN creation form. Enter your LAN's details, adding a 
description with [markdown formatting](https://en.wikipedia.org/wiki/Markdown#Example) if desired.

Anyone who signs into the LANager during the LAN you've created will be automatically added to the LAN's list of attendees, and taken
to the LAN's page, showing the LAN's timetabled events, guides and attendees list.

### Create Events & Guides

With your LAN created, you can now create events that will be happening during the LAN, and any relevant guides to help attendees
enjoy your party.

From the LAN's page, click the + button next to the **Events** and **Guides** headings to go to their creation forms.

#### Using Links

You can use markdown formatted links in LANs, guides and events, to help attendees find relevant information. For example, you can
write a single guide, and link to it on several event pages:

    If you need any help, please contact one of our [tournament staff](/lans/4/guides/3) 

It's recommended that you use relative links as above, so that if you change domain, the links continue to work.

#### Using Images

You can also upload images for displaying on these pages by clicking the "upload images" link below the "description" text box.
Once uploaded, click ⚙ > **Copy Markdown** next to the image, and then paste into the guide, event or LAN's 
**description** field.

### Display the Live Dashboard

To make it easy for your attendees to see at a glance which games are being played, events that are in progress, and any that are
upcoming, on a computer connected to a TV or projector visit the dashboard page by clicking ⚙ > **Dashboard**, or if you aren't 
logged in, simply visit:

`http://(your LANager install's address)/dashboard`

### Create and Award Achievements

Click ⚙ > **Achievements** and then click the + button to create achievements that can then be awarded to users. 

Once you've created one or more achievements, you can award them by clicking **Achievements** on the navigation bar, which will take
you to the list of achievements awarded to attendees of the current LAN. At the bottom of the page, choose the achievement and the
attendee to award it to, and click **Award**.

### Customise the Navigation Bar

Click ⚙ > **Navigation** to customise the links shown on the navigation bar. You can link to pages on the LANager, or 3rd party
sites, organise the links into dropdown menus, and choose the order that the links appear in the navbar or dropdown.

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
