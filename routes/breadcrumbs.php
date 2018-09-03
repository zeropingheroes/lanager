<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(config('app.name'), route('home'));
});

// Home > Role Assignments
Breadcrumbs::for('role-assignments.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.role-assignments'), route('role-assignments.index'));
});

// Home > Navigation Links
Breadcrumbs::for('navigation-links.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.navigation-links'), route('navigation-links.index'));
});

// Home > Navigation Links > Create
Breadcrumbs::for('navigation-links.create', function ($trail) {
    $trail->parent('navigation-links.index');
    $trail->push(__('title.create'), route('navigation-links.create'));
});

// Home > Navigation Links > Edit
Breadcrumbs::for('navigation-links.edit', function ($trail, $navigationLink) {
    $trail->parent('navigation-links.index');
    $trail->push(__('title.edit'), route('navigation-links.edit', $navigationLink));
});

// Home > Images
Breadcrumbs::for('images.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.images'), route('images.index'));
});

// Home > Images > Edit
Breadcrumbs::for('images.edit', function ($trail, $image) {
    $trail->parent('images.index');
    $trail->push(__('title.edit'), route('images.edit', $image));
});

// Home > Event Types
Breadcrumbs::for('event-types.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.event-types'), route('event-types.index'));
});

// Home > Event Types > Create
Breadcrumbs::for('event-types.create', function ($trail) {
    $trail->parent('event-types.index');
    $trail->push(__('title.create'), route('event-types.create'));
});

// Home > Event Types > Edit
Breadcrumbs::for('event-types.edit', function ($trail, $eventType) {
    $trail->parent('event-types.index');
    $trail->push(__('title.edit'), route('event-types.edit', $eventType));
});

// Home > Logs
Breadcrumbs::for('logs.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.logs'), route('logs.index'));
});

// Home > Logs > [Log]
Breadcrumbs::for('logs.show', function ($trail, $log) {
    $trail->parent('logs.index');
    $trail->push('#' . $log->id, route('logs.show', $log));
});

// Home > Games
Breadcrumbs::for('games.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.games'), route('games.in-progress'));
});

// Home > Games > Live
Breadcrumbs::for('games.in-progress', function ($trail) {
    $trail->parent('games.index');
    $trail->push(__('title.games-in-progress'), route('games.in-progress'));
});

// Home > Games > Recent
Breadcrumbs::for('games.recent', function ($trail) {
    $trail->parent('games.index');
    $trail->push(__('title.recently-played-games'), route('games.recent'));
});

// Home > Games > Owned
Breadcrumbs::for('games.owned', function ($trail) {
    $trail->parent('games.index');
    $trail->push(__('title.games-owned'), route('games.owned'));
});

// Home > Achievements
Breadcrumbs::for('achievements.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.achievements'), route('achievements.index'));
});

// Home > Achievements > [Achievement]
Breadcrumbs::for('achievements.show', function ($trail, $achievement) {
    $trail->parent('achievements.index');
    $trail->push($achievement->name, route('achievements.show', $achievement));
});

// Home > Achievements > Create
Breadcrumbs::for('achievements.create', function ($trail) {
    $trail->parent('achievements.index');
    $trail->push(__('title.create'), route('achievements.create'));
});

// Home > LANs
Breadcrumbs::for('lans.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.lans'), route('lans.index'));
});

// Home > LANs > Create
Breadcrumbs::for('lans.create', function ($trail) {
    $trail->parent('lans.index');
    $trail->push(__('title.create'), route('lans.create'));
});

// Home > LANs > [LAN]
Breadcrumbs::for('lans.show', function ($trail, $lan) {
    $trail->parent('lans.index');
    $trail->push($lan->name, route('lans.show', $lan));
});

// Home > LANs > [LAN] > Edit
Breadcrumbs::for('lans.edit', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.edit'), route('lans.edit', $lan));
});

// Home > LANs > [LAN] > Guides
Breadcrumbs::for('lans.guides.index', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.guides'), route('lans.guides.index', $lan));
});

// Home > LANs > [LAN] > Guides > Create
Breadcrumbs::for('lans.guides.create', function ($trail, $lan) {
    $trail->parent('lans.guides.index', $lan);
    $trail->push(__('title.create'), route('lans.guides.create', $lan));
});

// Home > LANs > [LAN] > Guides > [Guide]
Breadcrumbs::for('lans.guides.show', function ($trail, $lan, $guide) {
    $trail->parent('lans.guides.index', $lan);
    $trail->push($guide->title, route('lans.guides.show', ['lan' => $lan, 'guide' => $guide]));
});

// Home > LANs > [LAN] > Guides > [Guide] > Edit
Breadcrumbs::for('lans.guides.edit', function ($trail, $lan, $guide) {
    $trail->parent('lans.guides.show', $lan, $guide);
    $trail->push(__('title.edit'), route('lans.guides.edit', ['lan' => $lan, 'guide' => $guide]));
});

// Home > LANs > [LAN] > Events
Breadcrumbs::for('lans.events.index', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.events'), route('lans.events.index', $lan));
});

// Home > LANs > [LAN] > Events > Create
Breadcrumbs::for('lans.events.create', function ($trail, $lan) {
    $trail->parent('lans.events.index', $lan);
    $trail->push(__('title.create'), route('lans.events.create', $lan));
});

// Home > LANs > [LAN] > Events > [Event]
Breadcrumbs::for('lans.events.show', function ($trail, $lan, $event) {
    $trail->parent('lans.events.index', $lan);
    $trail->push($event->name, route('lans.events.show', ['lan' => $lan, 'event' => $event]));
});

// Home > LANs > [LAN] > Events > [Event] > Edit
Breadcrumbs::for('lans.events.edit', function ($trail, $lan, $event) {
    $trail->parent('lans.events.show', $lan, $event);
    $trail->push(__('title.edit'), route('lans.events.edit', ['lan' => $lan, 'event' => $event]));
});

// Home > LANs > [LAN] > Attendees
Breadcrumbs::for('lans.attendees.index', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.attendees'), route('lans.attendees.index', $lan));
});