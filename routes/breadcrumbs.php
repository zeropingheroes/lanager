<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(config('app.name'), route('home'));
});

// Home > Games
Breadcrumbs::for('games.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('title.games'), route('games.in-progress'));
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

// Home > LANs > [LAN] > Edit
Breadcrumbs::for('lans.edit', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.edit'), route('lans.edit', $lan));
});

// Home > LANs > [LAN]
Breadcrumbs::for('lans.show', function ($trail, $lan) {
    $trail->parent('lans.index');
    $trail->push($lan->name, route('lans.show', $lan));
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

// Home > LANs > [LAN] > Events > [Event]
Breadcrumbs::for('lans.events.show', function ($trail, $lan, $event) {
    $trail->parent('lans.events.index', $lan);
    $trail->push($event->name, route('lans.events.show', ['lan' => $lan, 'guide' => $event]));
});

// Home > LANs > [LAN] > Attendees
Breadcrumbs::for('lans.attendees.index', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push(__('title.attendees'), route('lans.attendees.index', $lan));
});