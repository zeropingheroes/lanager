<?php

// Home
Breadcrumbs::for(
    'home',
    function ($trail): void {
        $trail->push(config('app.name'), route('home'));
    }
);

// Home > Role Assignments
Breadcrumbs::for(
    'role-assignments.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.role-assignments'), route('role-assignments.index'));
    }
);

// Home > Navigation Links
Breadcrumbs::for(
    'navigation-links.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.navigation-links'), route('navigation-links.index'));
    }
);

// Home > Navigation Links > Create
Breadcrumbs::for(
    'navigation-links.create',
    function ($trail): void {
        $trail->parent('navigation-links.index');
        $trail->push(__('title.create'), route('navigation-links.create'));
    }
);

// Home > Navigation Links > Edit
Breadcrumbs::for(
    'navigation-links.edit',
    function ($trail, $navigationLink): void {
        $trail->parent('navigation-links.index');
        $trail->push(__('title.edit'), route('navigation-links.edit', $navigationLink));
    }
);

// Home > Images
Breadcrumbs::for(
    'images.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.images'), route('images.index'));
    }
);

// Home > Images > Edit
Breadcrumbs::for(
    'images.edit',
    function ($trail, $image): void {
        $trail->parent('images.index');
        $trail->push(__('title.edit'), route('images.edit', $image));
    }
);

// Home > Games
Breadcrumbs::for(
    'games.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.games'), route('games.in-progress'));
    }
);

// Home > Games > Live
Breadcrumbs::for(
    'games.in-progress',
    function ($trail): void {
        $trail->parent('games.index');
        $trail->push(__('title.games-in-progress'), route('games.in-progress'));
    }
);

// Home > Games > Recent
Breadcrumbs::for(
    'games.recent',
    function ($trail): void {
        $trail->parent('games.index');
        $trail->push(__('title.recently-played-games'), route('games.recent'));
    }
);

// Home > Games > Owned
Breadcrumbs::for(
    'games.owned',
    function ($trail): void {
        $trail->parent('games.index');
        $trail->push(__('title.games-owned'), route('games.owned'));
    }
);

// Home > Achievements
Breadcrumbs::for(
    'achievements.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.achievements'), route('achievements.index'));
    }
);

// Home > Achievements > [Achievement]
Breadcrumbs::for(
    'achievements.show',
    function ($trail, $achievement): void {
        $trail->parent('achievements.index');
        $trail->push($achievement->name, route('achievements.show', $achievement));
    }
);

// Home > Achievements > [Achievement] > Edit
Breadcrumbs::for(
    'achievements.edit',
    function ($trail, $achievement): void {
        $trail->parent('achievements.index');
        $trail->push($achievement->name, route('achievements.edit', $achievement));
    }
);

// Home > Achievements > Create
Breadcrumbs::for(
    'achievements.create',
    function ($trail): void {
        $trail->parent('achievements.index');
        $trail->push(__('title.create'), route('achievements.create'));
    }
);

// Home > LANs
Breadcrumbs::for(
    'lans.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.lans'), route('lans.index'));
    }
);

// Home > LANs > Create
Breadcrumbs::for(
    'lans.create',
    function ($trail): void {
        $trail->parent('lans.index');
        $trail->push(__('title.create'), route('lans.create'));
    }
);

// Home > LANs > [LAN]
Breadcrumbs::for(
    'lans.show',
    function ($trail, $lan): void {
        $trail->parent('lans.index');
        $trail->push($lan->name, route('lans.show', $lan));
    }
);

// Home > LANs > [LAN] > Edit
Breadcrumbs::for(
    'lans.edit',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.edit'), route('lans.edit', $lan));
    }
);

// Home > LANs > [LAN] > Guides
Breadcrumbs::for(
    'lans.guides.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.guides'), route('lans.guides.index', $lan));
    }
);

// Home > LANs > [LAN] > Guides > Create
Breadcrumbs::for(
    'lans.guides.create',
    function ($trail, $lan): void {
        $trail->parent('lans.guides.index', $lan);
        $trail->push(__('title.create'), route('lans.guides.create', $lan));
    }
);

// Home > LANs > [LAN] > Guides > [Guide]
Breadcrumbs::for(
    'lans.guides.show',
    function ($trail, $lan, $guide): void {
        $trail->parent('lans.guides.index', $lan);
        $trail->push($guide->title, route('lans.guides.show', ['lan' => $lan, 'guide' => $guide]));
    }
);

// Home > LANs > [LAN] > Guides > [Guide] > Edit
Breadcrumbs::for(
    'lans.guides.edit',
    function ($trail, $lan, $guide): void {
        $trail->parent('lans.guides.show', $lan, $guide);
        $trail->push(__('title.edit'), route('lans.guides.edit', ['lan' => $lan, 'guide' => $guide]));
    }
);

// Home > LANs > [LAN] > Events
Breadcrumbs::for(
    'lans.events.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.events'), route('lans.events.index', $lan));
    }
);

// Home > LANs > [LAN] > Events > Create
Breadcrumbs::for(
    'lans.events.create',
    function ($trail, $lan): void {
        $trail->parent('lans.events.index', $lan);
        $trail->push(__('title.create'), route('lans.events.create', $lan));
    }
);

// Home > LANs > [LAN] > Events > [Event]
Breadcrumbs::for(
    'lans.events.show',
    function ($trail, $lan, $event): void {
        $trail->parent('lans.events.index', $lan);
        $trail->push($event->name, route('lans.events.show', ['lan' => $lan, 'event' => $event]));
    }
);

// Home > LANs > [LAN] > Events > [Event] > Edit
Breadcrumbs::for(
    'lans.events.edit',
    function ($trail, $lan, $event): void {
        $trail->parent('lans.events.show', $lan, $event);
        $trail->push(__('title.edit'), route('lans.events.edit', ['lan' => $lan, 'event' => $event]));
    }
);

// Home > LANs > [LAN] > Events > [Event] > Create Discord Notification Message
Breadcrumbs::for(
    'lans.events.discord-notification-message.create',
    function ($trail, $lan, $event): void {
        $trail->parent('lans.events.show', $lan, $event);
        $trail->push(__('title.create-discord-notification-message'), route('lans.events.discord-notification-message.create', ['lan' => $lan, 'event' => $event]));
    }
);

// Home > LANs > [LAN] > Events > [Event] > Edit Discord Notification Message
Breadcrumbs::for(
    'lans.events.discord-notification-message.edit',
    function ($trail, $lan, $event): void {
        $trail->parent('lans.events.show', $lan, $event);
        $trail->push(__('title.edit-discord-notification-message'), route('lans.events.discord-notification-message.edit', ['lan' => $lan, 'event' => $event]));
    }
);

// Home > LANs > [LAN] > Attendees
Breadcrumbs::for(
    'lans.attendees.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.attendees'), route('lans.attendees.index', $lan));
    }
);

// Home > LANs > [LAN] > User Achievements
Breadcrumbs::for(
    'lans.user-achievements.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.achievements'), route('lans.user-achievements.index', $lan));
    }
);

// Home > Venue
Breadcrumbs::for(
    'venues.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.venues'), route('venues.index'));
    }
);

// Home > Venue > [Venue]
Breadcrumbs::for(
    'venues.show',
    function ($trail, $venue): void {
        $trail->parent('venues.index');
        $trail->push($venue->name, route('venues.show', $venue));
    }
);

// Home > Venue > [Venue] > Edit
Breadcrumbs::for(
    'venues.edit',
    function ($trail, $venue): void {
        $trail->parent('venues.index');
        $trail->push($venue->name, route('venues.edit', $venue));
    }
);

// Home > Venue > Create
Breadcrumbs::for(
    'venues.create',
    function ($trail): void {
        $trail->parent('venues.index');
        $trail->push(__('title.create'), route('venues.create'));
    }
);

// Home > LANs > [LAN] > Slides
Breadcrumbs::for(
    'lans.slides.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.slides'), route('lans.slides.index', $lan));
    }
);

// Home > LANs > [LAN] > Slides > Create
Breadcrumbs::for(
    'lans.slides.create',
    function ($trail, $lan): void {
        $trail->parent('lans.slides.index', $lan);
        $trail->push(__('title.create'), route('lans.slides.create', $lan));
    }
);

// Home > LANs > [LAN] > Slides > [Slide]
Breadcrumbs::for(
    'lans.slides.show',
    function ($trail, $lan, $slide): void {
        $trail->parent('lans.slides.index', $lan);
        $trail->push($slide->name, route('lans.slides.show', ['lan' => $lan, 'slide' => $slide]));
    }
);

// Home > LANs > [LAN] > Slides > [Slide] > Edit
Breadcrumbs::for(
    'lans.slides.edit',
    function ($trail, $lan, $slide): void {
        $trail->parent('lans.slides.index', $lan);
        $trail->push($slide->name, route('lans.slides.edit', ['lan' => $lan, 'slide' => $slide]));
    }
);

// Home > LANs > [LAN] > Discord Channel Webhooks
Breadcrumbs::for(
    'lans.discord-channel-webhooks.index',
    function ($trail, $lan): void {
        $trail->parent('lans.show', $lan);
        $trail->push(__('title.discord-channel-webhooks'), route('lans.discord-channel-webhooks.index', $lan));
    }
);

// Home > LANs > [LAN] > Discord Channel Webhooks > [Webhook] > Compose
Breadcrumbs::for(
    'lans.discord-channel-webhooks.messages.create',
    function ($trail, $lan, $webhook): void {
        $trail->parent('lans.discord-channel-webhooks.index', $lan);
        $trail->push(__('title.compose'), route('lans.discord-channel-webhooks.messages.create', ['lan' => $lan, 'discord_channel_webhook' => $webhook]));
    }
);

// Home > AllowedIpRange
Breadcrumbs::for(
    'allowed-ip-ranges.index',
    function ($trail): void {
        $trail->parent('home');
        $trail->push(__('title.allowed-ip-ranges'), route('allowed-ip-ranges.index'));
    }
);

// Home > AllowedIpRange > [AllowedIpRange] > Edit
Breadcrumbs::for(
    'allowed-ip-ranges.edit',
    function ($trail, $allowedIpRange): void {
        $trail->parent('allowed-ip-ranges.index');
        $trail->push($allowedIpRange->ip_range, route('allowed-ip-ranges.edit', $allowedIpRange));
    }
);

// Home > AllowedIpRange > Create
Breadcrumbs::for(
    'allowed-ip-ranges.create',
    function ($trail): void {
        $trail->parent('allowed-ip-ranges.index');
        $trail->push(__('title.create'), route('allowed-ip-ranges.create'));
    }
);
