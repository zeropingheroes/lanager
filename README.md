LANager
=======

LANager is a web application designed to make [LAN Parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Branch: `laravel-upgrade`

This unstable branch is where the work of upgrading to Laravel 5.6 is being done. Once complete this will be merged into the master branch.

## Development

### Requirements

- [Vagrant](https://www.vagrantup.com/downloads.html)
- [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
- [Composer](https://getcomposer.org/)

### Installation:

1. `git clone -b laravel-upgrade https://github.com/zeropingheroes/lanager && cd lanager`
2. `composer install`
3. `vagrant up`
4. `nano .env`
   1. `STEAM_API_KEY` - Enter your [Steam API Key](http://steamcommunity.com/dev/apikey)

Your development environment is now available at http://lanager.localhost:8000/

## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
	* [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see
	* Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues)
	* Or if you're really hardcore, request commit access

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)
