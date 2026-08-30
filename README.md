LANager
=======

|                                          Stable Branch |                                              Develop Branch |
|-------------------------------------------------------:|------------------------------------------------------------:|
|       [![Browser Tests][duskStableImg]][duskStableUrl] |          [![Browser Tests][duskDevelopImg]][duskDevelopUrl] |
| [![Feature Tests][featureStableImg]][featureStableUrl] |    [![Feature Tests][featureDevelopImg]][featureDevelopUrl] |
|          [![Unit Tests][unitStableImg]][unitStableUrl] |             [![Unit Tests][unitDevelopImg]][unitDevelopUrl] |
| [![Static Analysis][staticStableImg]][staticStableUrl] |    [![Static Analysis][staticDevelopImg]][staticDevelopUrl] |
|      [![PHP Code Style][pintStableImg]][pintStableUrl] |         [![PHP Code Style][pintDevelopImg]][pintDevelopUrl] |
|    [![Docker Image][dockerStableImg]][dockerStableUrl] | [![Docker Image (Dev)][dockerDevelopImg]][dockerDevelopUrl] |

LANager is a web application designed to make [LAN parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Contents

* [Features](#features)
* [Requirements](#requirements)
* [Setup](#setup)
* [Getting started](#getting-started)
* [Backup](#backup)
* [Update](#update)
* [Contributing](#contributing)

## Features

### Encourage socialising & participation

* **Games being played** by your LAN's attendees, updated every minute from Steam
* **Events timetable** showing your attendees what's on at your LAN
* **Achievements** created by you and awarded to attendees
* **Attendee profiles** with a link to their Steam profile, and their gameplay history at the LAN
* **Games in common** when viewing another attendee's profile
* **Games recently played** showing the top games most recently played by attendees at the LAN
* **Event Discord notifications** sent automatically when an event starts

### Broadcast useful information

* **Slides** showing games attendees are currently playing, and current and upcoming events, for TVs or projectors
  around your venue
* **Guides** written by you to help attendees learn about things like venue facilities, rules, or gameplay guides
* **Links** to other websites, such as your organisation's website, or a game statistics page

## Requirements

* [Internet access](https://www.youtube.com/watch?v=BPkSeXGJTPw)
* [Git](https://git-scm.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Setup

1. Clone the `lanager-docker-compose` project:

    ```bash
    git clone https://github.com/zeropingheroes/lanager-docker-compose
    ```

2. Copy `.env.example` to a new file named `.env`:

    ```bash
    cd lanager-docker-compose
    cp .env.example .env
    ```

3. Generate and copy a new application key:

    ```bash
    docker run --rm --entrypoint php -w /app zeropingheroes/lanager:stable artisan key:generate --show
    ```

4. Open the environment configuration file in a text editor:

    ```bash
    nano .env
    ```

5. Set the following configuration items:

    | Variable           | Set to                                                                                                     |
    |--------------------|------------------------------------------------------------------------------------------------------------|
    | `APP_URL`          | The URL you will access LANager through, without a trailing slash                                          |
    | `APP_TIMEZONE`     | Your location's [timezone](https://wikipedia.org/wiki/List_of_tz_database_time_zones#List)                 |
    | `STEAM_API_KEY`    | Your [Steam API Key](http://steamcommunity.com/dev/apikey)                                                 |
    | `DB_PASSWORD`      | A randomly generated password                                                                              |
    | `DB_ROOT_PASSWORD` | A different randomly generated password                                                                    |
    | `TRUSTED_PROXIES`  | (If running behind a reverse proxy) the IP ranges used by Docker, typically `172.16.0.0/12,192.168.0.0/16` |

6. Bring up the application:

    ```bash
    docker compose up --detach --wait
    ```

7. Initialise the database:

    ```bash
    ./initialise-database.sh
    ```

LANager should now be accessible at the `APP_URL` you specified.

## Getting started

### Become a Super Admin

The LANager assigns the "Super Admin" role to the first account that logs into it, so make sure you log in as soon as
you have completed installation. Super Admins can perform any action on the site, including assigning roles to other
users.

### Assign roles to other users

Attendees must sign in to LANager before you can assign them a role.

1. In the top-right, select ⚙ > **Role Assignments**
2. Select the **user** and the **role** (**Admin** or **Super Admin**) to assign
3. Select **Assign Role**

Admins can do everything a super admin can do, except assigning or revoking roles.

### Create a venue

Creating a venue is optional but allows you to set a street address and associate LANs with the venue.

1. In the top-right, select ⚙ > **Venues**
2. Select the **+** button
3. Enter the venue's **name** and **street address**
4. Select **Submit**

### Create a LAN

In the LANager, all events, guides, slides, and other items are associated with a LAN. Before you create any other items,
you must create a LAN:

1. In the top-right, select ⚙ > **LANs**
2. Select the **+** button
3. Enter your LAN's name, start and end date and time
4. Optionally:
   1. Set the LAN's **venue**
   2. Customise the default **message** sent to Discord when an event starts
5. Enable the **Published** checkbox
6. Select **Submit**

Your LAN is now visible to everyone. The LANager automatically adds anyone who logs in during the LAN to the LAN's 
list of attendees and redirects them to the LAN's page, which shows the timetable, guides, and attendee list.

### Create an event

Events are a useful way to timetable game tournaments, highlight big game sessions, schedule breaks and mealtimes,
and let people know when it's time to go home.

1. Go to the LAN's page
2. Select the **Events** tab
3. Select **Create**
4. Enter the event's **name**, **description**, and **start** and **end** date and time
5. Enable the **Published** checkbox
6. Select **Submit**

Your event is now visible to everyone on the LAN's timetable. Create as many events as you need to help organise your
LAN.

### Create a guide

Use guides to provide your attendees with useful information that will help them enjoy the LAN. For example,
game download links, gameplay guides, food and drink options, to provide a code of conduct for your event.

1. Go to the LAN's page
2. Select the **Guides** tab
3. Select **Create**
4. Enter the guide's **title**
5. Write the guide's **content**, using Markdown formatting and uploading images
6. Enable the **Published** checkbox
7. Select **Submit**

### Create slides

If you have a big TV or projector, use slides to show your attendees a looping slideshow of live data and useful
info, such as which event is starting next, which games people are playing, and how to log into the LANager.

1. Go to the LAN's page
2. Select the **Slides** tab
3. Select the **+** button
4. Enter the slide's **name**
5. Write the slide's **content**, using Markdown formatting and uploading images
6. Set the slide's **position** in the slideshow to determine when it will be shown
7. Set the slide's **duration** in seconds
8. To only show the slide at a particular time, set the slide's **start** and **end** dates and times
9. Enable the **Published** checkbox
10. Select **Submit**

Your slide will now be visible to everyone on the LAN's slideshow, available at the URL (no login required):

`/lans/{id}/slides/play`

### Create an achievement

Use achievements to reward attendees for accomplishments at your LAN, such as winning a tournament or completing
a challenge.

1. In the top-right, select ⚙ > **Achievements**
2. Select the **+** button
3. Enter the achievement's **name** and **description**
4. Optionally upload an **image** for the achievement
5. Select **Submit**

### Award an achievement

1. Go to the LAN's page
2. Select the **Achievements** tab
3. At the bottom of the page, choose the achievement and the attendee to award it to
4. Select **Award**

### Configure Discord channels

The LANager can send messages to Discord channels, for example, to notify attendees when an event is starting. To do
this, you first need to create a webhook for the channel in Discord, then add it to your LAN in the LANager.

You can configure up to two webhooks per LAN:

* **Live** - used to send messages to attendees, such as event notifications
* **Test** - used to preview messages formatting, images, and links before sending to attendees

Create a channel webhook in Discord:

1. Open **Server Settings** for your Discord server
2. Select **Integrations** → **Webhooks** → **New Webhook**
3. For **Name**, enter **LANager**
4. For **Channel**, select the channel you want the webhook to post messages to
5. Set the webhook's icon to the [LANager favicon](public/apple-touch-icon-152x152-precomposed.png)
6. Select **Copy Webhook URL**

Add the webhook to your LAN in the LANager:

1. Go to your LAN's page
2. Select the **Channels** tab
3. Choose whether the webhook is for the **Live** or **Test** channel
4. Paste the webhook URL you copied from Discord
5. Select **Submit**

Repeat these steps for both webhook purposes: `live` and `test`.

### Send event notifications to Discord

Once you have configured Discord channel webhooks for your LAN, you can set an event to automatically send a
message to Discord at the event's start time.

1. Go to the event's page
2. Select ⚙ → **Discord** → **Create Notification Message**
3. Enter the message you want to send, or leave it blank to use the LAN's default message if one is set. You can use
   the `{{event.name}}` and `{{event.url}}` placeholders, which are replaced with the event's details when the
   message is sent
4. Optionally, upload and attach images to the message
5. Make sure **Automatically send the message at the event's start time** is checked
6. If you have configured a `test` channel webhook, select **Preview in Test Channel** to send the message to the
   test channel and check how the message will look before saving
7. Select **Submit** to save

The LANager checks for events starting every minute and sends their notification message to the `live` Discord channel
automatically. You can also send a message manually at any time, or edit or delete it, from the event's page under
⚙ > **Discord**.

### Customise the navigation bar

Customise the links your attendees see in the navigation bar, linking to pages on the LANager or to third-party
sites, optionally organised into drop-down menus.

1. In the top-right, select ⚙ > **Navigation**
2. Add, edit, reorder, or remove links
3. Optionally set a link's **parent** to create a drop-down menu
3. Select **Submit** to save changes

### Create an API token

LANager exposes a REST API under `/api`. Most of it is public and read-only, but a few actions (such as sending a
Discord notification message) require you to authenticate. To let an external client (a bot, a script, or another
app) perform these actions on your behalf, issue yourself a personal access token:

1. In the top-right, select your avatar > **API Tokens**
2. Enter a **name** for the token, so you can recognise it later
3. Select **Create API Token**
4. Copy the token value shown as it is only ever shown once

Use the token by sending it as a `Bearer` token in the `Authorization` header of your API requests. You can revoke
a token at any time from the same page.

## Backup

Back up LANager's configuration, database data, and uploaded images:

```bash
cd lanager-docker-compose
./backup.sh
```

### Restore a backup

```bash
cd lanager-docker-compose
./backup-restore.sh <file>
```

## Update

1. Enter the `lanager-docker-compose` repository:

    ```bash
    cd lanager-docker-compose
    ```

2. Back up your data:

    ```bash
    ./backup.sh
    ```

3. Get the latest version of the Docker compose files and scripts:

    ```bash
    git pull
    ```

4. Run the update script:

    ```bash
    ./update.sh
    ```

## Contributing

For information on how to set up LANager for development and contribute, read [CONTRIBUTING.md](CONTRIBUTING.md).

[duskStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/browser-tests.yml/badge.svg?branch=stable

[duskStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/browser-tests.yml

[duskDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/browser-tests.yml/badge.svg?branch=develop

[duskDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/browser-tests.yml

[featureStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/feature-tests.yml/badge.svg?branch=stable

[featureStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/feature-tests.yml

[featureDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/feature-tests.yml/badge.svg?branch=develop

[featureDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/feature-tests.yml

[unitStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/unit-tests.yml/badge.svg?branch=stable

[unitStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/unit-tests.yml

[unitDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/unit-tests.yml/badge.svg?branch=develop

[unitDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/unit-tests.yml

[staticStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/static-analysis.yml/badge.svg?branch=stable

[staticStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/static-analysis.yml

[staticDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/static-analysis.yml/badge.svg?branch=develop

[staticDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/static-analysis.yml

[pintStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/php-code-style.yml/badge.svg?branch=stable

[pintStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/php-code-style.yml

[pintDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/php-code-style.yml/badge.svg?branch=develop

[pintDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/php-code-style.yml

[dockerStableImg]:https://github.com/zeropingheroes/lanager/actions/workflows/docker-image.yml/badge.svg

[dockerStableUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/docker-image.yml

[dockerDevelopImg]:https://github.com/zeropingheroes/lanager/actions/workflows/docker-image-dev.yml/badge.svg?branch=develop

[dockerDevelopUrl]:https://github.com/zeropingheroes/lanager/actions/workflows/docker-image-dev.yml
