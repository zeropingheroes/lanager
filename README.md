LANager
=======

LANager is a web application designed to make [LAN Parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Features

### Enhance Participation

* **Games in progress**, updated every minute from Steam
* **Timetable of events**, optionally allowing users to sign up to events
* **Achievements**, created by admins, and awardable to attendees

### Boost Social Interaction
* **Attendee profiles** with links to add or message on Steam
* **Attendee list** with their current status

### Broadcast Useful Information
* **Info pages** for attendees to find out about the venue, rules etc, editable by admins 
* **Links** to other sites e.g. game stats, organiser's website 
* **Shouts** allowing attendees and admins to broadcast short messages
* **Live Dashboard** showing current and next events, games in progress and shouts, for big screen display

### Open for Integration
* **RESTful API** with open read access and API key guarded write access to all resources
* **Events dispatcher** allowing for hooking in of extra operations 
* **Comprehensive logging** of events allowing for debugging and auditing

And much more planned - check the [issue tracker](https://github.com/zeropingheroes/lanager-core/issues?labels=enhancement&milestone=&page=1&state=open) for future enhancements

# Screenshots

![Games](docs/screenshots/games.png)
![Event - View](docs/screenshots/event-signups.png)
![User list](docs/screenshots/people.png)
![Profile](docs/screenshots/profile.png)
![Servers](docs/screenshots/servers.png)
![Timetable - Day view](docs/screenshots/lanager-timetable.png)
![Timetable - Week view](docs/screenshots/lanager-timetable-2.png)
![Shouts](docs/screenshots/shouts.png)
![Info page - View](docs/screenshots/info_0.png)
![Info page - View](docs/screenshots/info-admin.png)
![Profile (with admin options)](docs/screenshots/profile-admin.png)
![Shouts (with admin options)](docs/screenshots/shouts-admin.png)
![Event - Edit](docs/screenshots/event-edit.png)
![Info Page - Create](docs/screenshots/info-admin-2.png)
![Custom Links](docs/screenshots/links.png)

## Requirements

* Server running Ubuntu Server 14.04 (_with shell access - basic web hosting is not supported_)
* [Steam API Key](http://steamcommunity.com/dev/apikey)
* Internet access

## Installation

1. Install the project's packaged dependencies:

    ```bash
    sudo apt install git php5-common php5-cli php5-mcrypt php5-curl php5-mysql libapache2-mod-php5 mysql-server apache2 zip
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer
    ```

2. Enable Apache and PHP modules:

    ```bash
    sudo a2enmod rewrite
    sudo php5enmod mcrypt curl
    ```

3. Configure Apache to use the project's public directory as the web root:

    1. Edit the default site configuration:

        ```bash
        sudo nano /etc/apache2/sites-enabled/000-default.conf
        ```

    2. Change the `DocumentRoot` line to:
    
        ```apacheconfig
        DocumentRoot /var/www/lanager/public
        ```

    3. Add the following lines inside the `<VirtualHost> block`:
    
        ```apacheconfig
        <Directory /var/www/lanager/public>
            AllowOverride All
        </Directory>
        ```

4. Clone the project:

    ```bash
    git clone https://github.com/zeropingheroes/lanager.git /var/www/lanager
    ```

5. Install the project's dependencies:

    ```bash
    cd /var/www/lanager
    composer install
    ```
    
6. Set permissions:

    ```bash
    chown -R www-data:www-data /var/www/lanager
    chmod -R 777 /var/www/lanager/app/storage
    ```

7. Create a MySQL user and database and grant the required privileges:
    
    ```bash
    mysql -u root -p
    ```
    
    ```mysql
    CREATE DATABASE lanager;
    CREATE USER 'lanager'@'localhost' IDENTIFIED BY 'type a password here';
    GRANT ALL PRIVILEGES ON lanager.* TO 'lanager'@'localhost';
    FLUSH PRIVILEGES;
    quit
    ```

8. Set the database password in the database config file:

    ```bash
    nano /var/www/lanager/app/config/database.php
    ```

9. Set your [Steam Web API key](http://steamcommunity.com/dev/apikey) in the steam config file:

    ```bash
    nano /var/www/lanager/app/config/lanager/steam.php
    ```

10. Set your [timezone](http://php.net/manual/en/timezones.php):

    ```bash
    nano /var/www/lanager/app/config/app.php
    ```

11. Run the LANager installation command:

    ```bash
    cd /var/www/lanager
    php artisan lanager:install
    ```

12. Schedule the LANager Steam state import command to run at 1 minute intervals:

    ```bash
    chmod +x /var/www/lanager/SteamImportUserStates.sh
    crontab -e
    ```

    ```cron
    */1 * * * * /var/www/lanager/SteamImportUserStates.sh 2>&1 | /usr/bin/logger -t lanager-steam-import-user-states
    ```
    
13. Restart Apache

    ```bash
    /etc/init.d/apache2 restart
    ```

## Usage

Once you have completed the installation, you can sign in via Steam. The first user to sign in will be granted the "Super Admin" role, allowing you to assign roles to other users who sign in.

Look through the configuration files inside `/var/www/lanager/app/config/lanager/` which will allow you to tailor your installation to your event.  

*This section will be expanded upon as features are solidified.*

## Updating

To update to the latest version,  directory simply run the following commands:

1. Back up your config files:

    `cp -R /var/www/lanager/app/config ~/lanager-config-backup`

2. Back up your database data:

    `mysqldump lanager -u lanager -p --result-file=lanager.sql`

3. Move to the root of the project directory:

    `cd /var/www/lanager`

4. Reset all project files to their defaults:

    `git reset --hard`

5. Empty the database data and structure:

    1. `mysql -u root -p`
    2. `DROP DATABASE lanager;`
    3. `CREATE DATABASE lanager;`
    4. `quit;`

6. Get the latest version from the project repository:
    
    `git pull origin`

7. Install / update dependencies:

    `composer install`

8. Manually set your config options from the backed up files

9. Re-allow full read and write access on the app's storage directory:

    `chmod -R 777 /var/www/lanager/app/storage`
    
10. Change the owner of the project files and folders to `www-data`:

    `chown -R www-data:www-data /var/www/lanager`

11. Re-run the installation command:
    
    `php artisan lanager:install`

Be warned that config file and database structure may have changed between versions so pay attention when copying data from backups from old versions.


## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
    * [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see
    * Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues)
    * Or if you're really hardcore, request commit access

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)
