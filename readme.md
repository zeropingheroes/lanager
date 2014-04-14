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
* Windows / Linux / OS X
* Apache Web server
* PHP 5.3.7+ (with cURL & MCrypt)
* MySQL
* [Composer](https://getcomposer.org/)
* [Steam API Key](http://steamcommunity.com/dev/apikey)
* An Internet connection
* Shell access to the server - **web hosting alone will not work!** *

WAMP, LAMP and MAMP are a quick way to satisfy most of the above, just check that the version you download includes PHP 5.3.7+

\* *Steam user states are updated using a batch script so you must be able to run commands and schedule tasks / cron jobs* 

## Installation

LANager uses the excellent PHP dependency manager [Composer](http://getcomposer.org/) which makes installing and updating a breeze. **You should not download the source directly from GitHub** - it is sufficient to follow the below instructions:

1. [Download and install Composer](http://getcomposer.org/download/)
2. From a terminal in the folder you wish to install LANager, run `composer create-project zeropingheroes/lanager`
3. Configure your web server to use `lanager/public/` as the root web directory
4. Edit `app/config/app.php` setting your installation's URL and application key 
5. In the `lanager/` directory, run `php artisan lanager:install` from the terminal
6. Schedule the artisan command `steam:import-user-states` to run at 1 minute intervals
	* On Windows
		1. Add a task for `SteamImportUserStates.bat` in [Task Scheduler](http://support.microsoft.com/kb/226795)
	* On *nix
		1. From a terminal run `crontab -e`
		2. Add the following to the end of the file:
		`*/1 * * * * /path/to/lanager/SteamImportUserStates.sh >> /dev/null 2>&1`     

## Vagrant

If you want to try out the project quickly and easily, you can use [Vagrant](http://www.vagrantup.com/about.html) to create a virtual machine with everything the project needs to run. Here's how:

1. Follow steps 1 and 2 from the *Installation* section above
2. [Download and install Vagrant](http://downloads.vagrantup.com/)
3. From a terminal in the `lanager/` directory, run `vagrant up`
4. Add `lanager.dev` to your system's `hosts` file, pointing it to `127.0.0.1`
6. Follow steps 4 and 5 from the *Installation* section above

When you're finished either run `vagrant destroy` to remove the virtual machine entirely, or `vagrant suspend` to save it for use later. 

## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
	* [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see
	* Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues)
	* Or if you're really hardcore, request commit access

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)
