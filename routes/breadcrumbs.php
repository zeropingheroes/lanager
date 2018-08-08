<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > LANs
Breadcrumbs::for('lans.index', function ($trail) {
    $trail->parent('home');
    $trail->push('LANs', route('lans.index'));
});

// Home > LANs > [LAN]
Breadcrumbs::for('lans.show', function ($trail, $lan) {
    $trail->parent('lans.index');
    $trail->push($lan->name, route('lans.show', $lan->id));
});

// Home > LANs > [LAN] > Pages
Breadcrumbs::for('lans.pages.index', function ($trail, $lan) {
    $trail->parent('lans.show', $lan);
    $trail->push('Pages', route('lans.pages.index', $lan->id));
});

// Home > LANs > [LAN] > Pages > [Page]
Breadcrumbs::for('lans.pages.show', function ($trail, $lan, $page) {
    $trail->parent('lans.pages.index', $lan);
    $trail->push($page->title, route('lans.pages.show', ['lan' => $lan->id, 'page' => $page->id]));
});