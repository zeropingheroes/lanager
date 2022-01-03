LANager
=======
[![Docker](https://github.com/zeropingheroes/lanager/actions/workflows/build-docker.yml/badge.svg?branch=develop)](
https://github.com/zeropingheroes/lanager/actions/workflows/build-docker.yml)
[![Behat](https://github.com/zeropingheroes/lanager/actions/workflows/run-behat.yml/badge.svg?branch=develop)](
https://github.com/zeropingheroes/lanager/actions/workflows/run-behat.yml)
[![StyleCI](https://github.styleci.io/repos/14088050/shield?branch=develop)](
https://github.styleci.io/repos/14088050?branch=develop)

LANager is a web application designed to make [LAN parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Features

### Encourage socialising & participation

* **Games being played** - by your LAN's attendees, updated every minute from Steam
* **Events timetable** - so your attendees know what's on at the LAN
* **Achievements** - created by you and awarded to attendees
* **Attendee profiles** - with a link to their Steam profile, and their gameplay history at the LAN
* **Games in common** - when viewing another attendee's profile
* **Games recently played** - showing the top games most recently played by attendees at the LAN

### Broadcast useful information
* **Live Dashboard** - showing games attendees are currently playing, and current and upcoming events, for TVs or
  projectors around your venue
* **Guides** - written by you, to help attendees learn about things like venue facilities, rules, or gameplay guides
* **Links** - to other websites, such as your organisation's website, or a game statistics page

## Requirements

* Internet access
* [Docker Engine](https://docs.docker.com/engine/install/#server)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Setup

1. Clone the `lanager-docker-compose` project:

    ```bash
    git clone --branch develop https://github.com/zeropingheroes/lanager-docker-compose
    ```

2. Copy `.env.example` to a new file named `.env`:

    ```bash
    cd lanager-docker-compose
    cp .env.example .env
    ```

3. Open the environment configuration file in a text editor:
   ```bash
   nano .env
   ```

4. Set the following configuration items:
   1. Set `APP_KEY` to `base64:` followed by a [randomly generated 32 character base64 string](https://www.google.com/search?q=random+base64)
   2. Set `APP_URL` to the URL you will access LANager through, including the trailing slash, e.g. `https://example.com/`
   3. Set `APP_TIMEZONE` to your location's [timezone](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones#List)
   4. Set `STEAM_API_KEY` to your [Steam API Key](http://steamcommunity.com/dev/apikey)
   5. Set `GOOGLE_API_KEY` to your [Google API Key](https://console.cloud.google.com/apis/) with access to the [Maps
  embed API](https://developers.google.com/maps/documentation/embed/map-generator)
   6. Set `DB_PASSWORD` to a [randomly generated password](https://www.google.com/search?q=password+generator)
   7. Set `DB_ROOT_PASSWORD` to a different randomly generated password

5. Bring up the application:

    ```bash
   docker-compose up --detach
    ```

6. After waiting a minute for the database container to be ready, initialise the database:

    ```bash
   ./initialise-database.sh
    ```

LANager should now be accessible at http://localhost, or at the URL you specified in `APP_URL`, providing you've
created a corresponding DNS `A` record for the Docker host's IP address, and allowed ports `80` and `443` through
the Docker host's firewall.

## Troubleshooting

- Run `docker-compose down --rmi local --volumes` to delete the database data and LANager container image, and then
  retry the setup steps above
- Edit your `.env` file and enable debugging:
  ```bash
  APP_ENV=staging
  APP_DEBUG=true
  ```
  **Important:** Remove these lines after troubleshooting to avoid leaking sensitive data

If you get stuck, [create an issue](https://github.com/zeropingheroes/lanager/issues) with the details of what
you're experincing:
- The commands you've run
- The output of `docker-compose up`
- The output of `docker logs app`
- Any errors displayed in your browser

## Getting started

To set up the LANager for your next LAN party, you need to create a LAN page, then add Events (such as scheduled games
or lunch breaks) and Guides (such as game rules) to that LAN page.

### Become a Super Admin

The LANager assigns the "Super Admin" role to the first account that logs into it, so make sure you log in as soon as
you have completed installation.

Super Admins can perform any action on the site, including assigning roles to other users.

### Create your LAN page

The first thing you need to do is to create a LAN page in the LANager. This process is the same whether your LAN party
is one day or multiple days. You need to do this before you can create an event schedule, publish any guides or award
achievements.

Log into the LANanger, and go to ⚙ > **LANs**, then select the **+** button to go to the LAN page creation form. Enter
your LAN's details, and add a description with [markdown formatting](https://en.wikipedia.org/wiki/Markdown#Example)
if you want to.

The LANager automatically adds anyone who signs into the LANager during the LAN party to the LAN's list of attendees.
For each attendee it displays the current LAN page, which contains the LAN's timetabled events, guides and attendees
list.

### Create Events & Guides

Once you have a LAN page for your LAN party, you can create Events and Guides to help attendees enjoy your party.

* **Events** are a useful way to timetable game tournaments, highlight big game sessions, schedule breaks and mealtimes,
and let people know when it's time to go home
* **Guides** are a useful way to provide people with the rules and download links to the games you're playing, to let
them know where the nearest shops and restaurants are, to provide a code of conduct for your event, and to communicate
any other information you think your guests might need.

From the LAN page, click the **+** button next to the **Events** and **Guides** headings to go to their creation forms.

#### Using links

You can use markdown-formatted links in LANs, guides and events. For example, you can write a single guide, and insert
a link to it on several event pages:

    If you need any help, please contact one of our [tournament staff](/lans/4/guides/3)

Where possible, it's good practice to use relative links as demonstrated above, so that if you change your domain, the
links continue to work.

#### Using images

You can upload images to LAN pages, Events and Guides. To do this, follow these steps:

1. Below the "description" text box, select "upload images".
2. Upload the image you want to use.
3. Next to the image, select ⚙ > **Copy Markdown**
4. Paste the markdown into the guide, event or LAN's **description** field, in the location you want it to appear in the
text.

### Display the Live Dashboard

The Live Dashboard make it easy for your attendees to see at a glance which games are being played, events that are in
progress, and any that are upcoming.

To display the Live Dashboard, go to ⚙ > **Dashboard**. Alternatively, if you aren't logged in, visit the following
URL:

`http://(your LANager install's address)/dashboard`

It's useful to display this on a TV or projector that everyone can see.

### Create and award Achievements

Click ⚙ > **Achievements** and then click the **+** button to create achievements that you can award to users.

To award an Achievement to an attendee, go to the navigation bar and select **Achievements**. This opens the list of
Achievements you have awarded to attendees of the current LAN. At the bottom of the page, choose the Achievement and the
attendee to award it to, then select **Award**.

### Customise the navigation bar

Click ⚙ > **Navigation** to customise the links shown on the navigation bar. You can link to pages on the LANager or
to third-party sites, organise the links into drop-down menus, and choose the order that the links appear in the navbar
or dropdown.

## Backup

Run `./backup.sh` to back up LANager's configuration, database data and uploaded images.

### Restore a backup

Run `./backup-restore.sh <file>` to restore a backup.

## Migrating an existing installation to Docker

If you have an existing LANager installation that you would like to migrate to docker, follow the below steps.

1. Create a temporary directory 
  ```bash
   mkdir -p /tmp/lanager/images
   ```

2. Dump your existing MySQL data into a file:
  ```bash
  sudo mysqldump -uroot --add-drop-database --databases lanager > /tmp/lanager/lanager.sql
  ```

3. Stop MySQL, Nginx & PHP running on your server:
  ```bash
  sudo systemctl stop mysql nginx php7.2-fpm
  sudo systemctl start mysql nginx php7.2-fpm
  ```

4. Copy your existing uploaded images into the temporary directory:
  ```bash
  cp /var/www/lanager/storage/app/public/images/* /tmp/lanager/images
  ```

5. Back up your existing `.env` file:
  ```bash
  cp .env .env.original
  ```

6. Update your existing `.env` file:
  ```bash
  nano .env
  ```
  ```
  APP_ENV=staging
  DB_DATABASE=lanager
  DB_HOST=db
  DB_ROOT_PASSWORD= (generate a root password)
  LOG_CHANNEL=stdout
  APP_LOCALE=en
  ```

7. Bring the containers up:
  ```bash
  docker-compose up --detach
  ```

8. Load the environment file containing the database details into the current shell
  ```bash
  source .env
  ```

9. Restore the database data dump into the `db` container using a temporary mysql image:
  ```bash
  docker run -i -e "MYSQL_PWD=$DB_ROOT_PASSWORD" --network lanager_app-network --rm mysql:8 \
  mysql "-h$DB_HOST" -uroot "$DB_DATABASE" < /tmp/lanager/lanager.sql
  ```

10. Restore the uploaded images into the Laravel storage volume:
  ```bash
    docker run --rm --volumes-from app -v /tmp/lanager/:/restore php:7.4-fpm cp /restore/images/* \
  /var/www/lanager/storage/public/images/
  ```

11. Open your browser and test, and if all is well, uninstall MySQL, PHP and NGINX:
  ```bash
  sudo apt remove mysql nginx php7.2
  ```

13. Remove the temporary directory:
  ```bash
  rm -rf /tmp/lanager
  ```

## Development

### Development environment setup

To get LANager set up in a development environment, the steps are nearly identical to the production setup guide, but
before running `docker-compose up`, follow these steps:
1. Update these values in the `.env` file:
   ```
   APP_ENV=local
   APP_DEBUG=true
   ```
2. Make a copy of `docker-compose.override.yml.example` and name it `docker-compose.override.yml`, to configure
   Docker to bind-mount the project directory on your host computer into the Docker container
3. Run the command `UID=$(id -u) GID=$(id -g)` to give Docker your user's ID and group ID, so it can read and write
   to the project directory on your host computer
4. Run  `docker-compose up --detach`
5. Visit `http://localhost`

The container will run the code from your host computer, rather than the static copy of the code in the container's
image, so any changes you make to the files in the project directory will be seen by the running containers.

### Start and stop the development environment

To stop the development environment run `docker-compose stop`. When you're ready to start developing again run
`docker-compose start`.

### Destroy the development environment

To destroy the development environment and the database data volume, run:
1. `docker-compose down --volumes db-data && rm storage/.install-completed`

Follow the setup steps above to get a fresh development environment.

### Recompiling JavaScript & CSS assets

To recompile JavaScript & CSS assets, run:
1. `docker run -it --rm -v "$PWD":/var/www/html -w /var/www/html node:14-alpine npm install`
2. `docker run -it --rm -v "$PWD":/var/www/html -w /var/www/html node:14-alpine npm run dev`

To recompile whenever changes to files are detected, run:
1. `docker run -it --rm -v "$PWD":/var/www/html -w /var/www/html node:14-alpine npm run watch-poll`

To recompile minified versions suitable for committing, run:
1. `docker run -it --rm -v "$PWD":/var/www/html -w /var/www/html node:14-alpine npm run prod`

### Running tests

To run the functional test suite, run:
1. `docker exec -it bash`
2. `php artisan serve --env=testing &`
3. `export BEHAT_PARAMS='{"extensions" : {"Behat\\MinkExtension" : {"base_url" : "http://localhost:8000/"}}}';`
4. `vendor/bin/behat`

To re-run tests, simply re-run `vendor/bin/behat`.

Once you've finished running tests:
1. Run `fg` to bring Laravel's built-in webserver to the foreground
2. Press ctrl + C to exit it
3. Press ctrl + D to exit the container's shell

## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
    * [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see.
    * Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues).
    * If you're really hardcore, request commit access.

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)

Enjoy using the LANager!
