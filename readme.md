LANager
=======

LANager is a web application designed to make [LAN Parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Goals & Features

* Enhance participation
	* [Games in progress](http://zeropingheroes.co.uk/wp-content/gallery/lanager/games.png) updated every minute from Steam
	* [Servers being played on](http://zeropingheroes.co.uk/wp-content/gallery/lanager/servers.png) with join links, again from Steam
	* [Events](http://zeropingheroes.co.uk/wp-content/gallery/lanager/lanager-timetable.png) system optionally allowing users to [join events](http://zeropingheroes.co.uk/wp-content/gallery/lanager/event-signups.png)
	* [Shouts](http://zeropingheroes.co.uk/wp-content/gallery/lanager/shouts.png) allowing advertising of games
	* [Files](http://zeropingheroes.co.uk/wp-content/gallery/lanager-old/files.png) that may be required to join in *
* Boost social interaction
	* [Attendee profiles](http://zeropingheroes.co.uk/wp-content/gallery/lanager/profile.png) with links to add or message on Steam
	* [List of attendees](http://zeropingheroes.co.uk/wp-content/gallery/lanager/people.png) with their current status
* Broadcast information
	* [Info pages](http://zeropingheroes.co.uk/wp-content/gallery/lanager/info_0.png) so that everyone can find out about the venue, food or tournament rules  
	* [Links](http://zeropingheroes.co.uk/wp-content/gallery/lanager/links.png) to other sites e.g. game stats, organiser's website 
* Allow attendee feedback
	* [Request music & videos](http://zeropingheroes.co.uk/wp-content/gallery/lanager/playlist.png) for play-out on a big screen
	* [Watch & listen](http://zeropingheroes.co.uk/wp-content/gallery/lanager-old/playlist_screen.png) to requested music & videos
* API access
	* Allow user status, game and server information for non-steam applications to be used *
	* Make data available for events, users, info pages, shouts, videos etc *
	* Generally avoid tying the project to the Steam platform *
* Much more
	* Check the [issue tracker](https://github.com/zeropingheroes/lanager-core/issues?labels=enhancement&milestone=&page=1&state=open) for more planned enhancements

\* *Planned but not yet implemented*

## Requirements
* Web server - *Apache 2.4.x recommended*
* Database server - *MySQL 5.5.x recommended*
* Git 1.9.x
* PHP 5.5.x
* Composer 1.x
* A [Steam Account](https://store.steampowered.com/join/)
* An Internet connection
* Shell access to the server - **basic web hosting alone will not work!** 

## Installation on [Ubuntu Server 14.04](http://www.ubuntu.com/download/server)

*Advanced users will be able to deploy LANager to Windows and OS X, though LANager has not been fully tested on these OSes.*

1. Install the project's packaged dependencies:

	`sudo apt-get install git php5-common php5-cli php5-mcrypt php5-curl php5-mysql libapache2-mod-php5 mysql-server apache2`

2. Enable Apache and PHP modules:

	`sudo a2enmod rewrite`

	`sudo php5enmod mcrypt curl`

3. Install Composer:

	`sudo curl -sS https://getcomposer.org/installer | php`

	`sudo mv composer.phar /usr/bin/composer`

4. Clone the project:

	`sudo git clone https://github.com/zeropingheroes/lanager.git`

5. Move the project to Apache's web root directory:

	`sudo mv lanager /var/www/lanager`

6. Install the project's dependencies:

	`cd /var/www/lanager`
	
	`sudo composer update`

7. Configure Apache to use the LANager's public directory as the web root:

	1. Edit the default site configuration:

		`sudo nano /etc/apache2/sites-enabled/000-default.conf`

	2. Change the DocumentRoot line to:
	
		`DocumentRoot /var/www/lanager/public`

	3. Add the following lines to allow .htaccess files to set options in this directory (for [pretty URLs](http://laravel.com/docs/4.2/installation#pretty-urls)):
	
		```
		<Directory /var/www/lanager/public>
			AllowOverride All
		</Directory>
		```

8. Allow full read and write access on the app's storage directory:

	`sudo chmod -R 777 /var/www/lanager/app/storage`
	
9. Create a MySQL user and database and grant the required privileges:
    
	1. Run `mysql -u root -p`
	2. Type your MySQL root user password you chose during MySQL package installation
	3. Once you are at the `mysql>` prompt, run each of the following in turn:
		1. `CREATE DATABASE lanager;`
		2. `CREATE USER 'lanager'@'localhost' IDENTIFIED BY 'type a password here';`
		3. `GRANT ALL PRIVILEGES ON lanager.* TO 'lanager'@'localhost';`
		4. `FLUSH PRIVILEGES;`
		5. `quit;`

10. Set the database password in the database config file:

	1. `sudo nano /var/www/lanager/app/config/database.php`
	2. Change the `password` line to your chosen password in the previous step

11. Set your [Steam Web API key](http://steamcommunity.com/dev/apikey) in the steam config file:

	`sudo nano /var/www/lanager/app/config/lanager/steam.php`

12. Run the LANager installation command:

	`cd /var/www/lanager`
	
	`sudo php artisan lanager:install`

13. Schedule the LANager Steam state import command to run at 1 minute intervals:

	1. Make the script file executable
		
		`sudo chmod +x /var/www/lanager/SteamImportUserStates.sh`
	
	2. Add the script as a cron job
		
		1. Run `crontab -e`
		2. Add the following to the end of the file:
		`*/1 * * * * /var/www/lanager/SteamImportUserStates.sh >> /dev/null 2>&1`

## Usage

Once you have completed the installation, you can sign in via Steam. The first user to sign in will be granted the "Super Admin" role, allowing you to assign roles to other users who sign in.

Look through the configuration files inside `/var/www/lanager/app/config/lanager/` which will allow you to tailor your installation to your event.  

*This section will be expanded upon as features are solidified.*

## Updating

To update to the latest version,  directory simply run the following commands:

1. Back up your config files:

	`sudo cp /var/www/lanager/app/config ~/lanager-config-backup`

2. Back up your database data:

	`mysqldump lanager -u lanager -p --result-file=lanager.sql`

3. Move to the root of the project directory:

	`cd /var/www/lanager`

4. Reset all project files to their defaults:

	`sudo git reset --hard`

5. Empty the database data and structure:

	1. `mysql -u root -p`
	2. `DROP DATABASE lanager;`
	3. `CREATE DATABASE lanager;`
	4. `quit;`

5. Get the latest version from the project repository:
	
	`sudo git pull origin`

6. Install / update dependencies:

	`sudo composer update`

7. Manually set your config options from the backed up files (see steps 10 and 11 from the Install guide)

8. Re-allow full read and write access on the app's storage directory:

	`sudo chmod -R 777 /var/www/lanager/app/storage`

8. Re-run the installation command:
	
	`sudo php artisan lanager:install`

Be warned that config file and database structure may have changed between versions so pay attention when copying data from backups from old versions.


## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
	* [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see
	* Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues)
	* Or if you're really hardcore, request commit access

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)
