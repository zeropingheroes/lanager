<?php

/**
 * Home
 */
Route::get('/', 'LanController@show')
    ->name('home');

/**
 * Login
 */
Route::get('login', 'AuthController@showLoginForm')
    ->middleware(['guest'])
    ->name('login');

Route::get('auth/{provider}', 'AuthController@redirectToProvider')
    ->middleware(['guest'])
    ->name('auth');

Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback')
    ->middleware(['guest'])
    ->name('auth.callback');

/**
 * Logout
 */
Route::post('logout', 'AuthController@logout')
    ->middleware(['auth'])
    ->name('logout');

/**
 * Roles & Role Assignments
 */
Route::resource('role-assignments', 'RoleAssignmentController', ['except' => ['show', 'edit', 'update']]);

/**
 * Logs
 */
Route::resource('logs', 'LogController', ['only' => ['index', 'show']]);
Route::patch('logs', 'LogController@patch')
    ->name('logs.patch');

/**
 * Games
 */
Route::get('games', 'GameController@index')
    ->name('games.index');

/**
 * LANs
 */
Route::resource('lans', 'LanController');

/**
 * Info Pages
 */
Route::resource('lans.pages', 'PageController', ['except' => 'show']);
Route::get('lans/{lan}/pages/{page}/{slug?}', 'PageController@show')
    ->name('lans.pages.show');

/**
 * Events & Event Types
 */
Route::resource('lans.events', 'EventController');
Route::resource('event-types', 'EventTypeController');
Route::get('/schedule', function () {
    return view('pages.events.schedule');
})->name('schedule');

/**
 * Users & Attendeees
 */
Route::resource('users', 'UserController', ['only' => ['show', 'destroy']]);
Route::resource('lans.attendees', 'AttendeeController', ['only' => ['index']]);

/**
 * Navigation Links
 */
Route::resource('navigation-links', 'NavigationLinkController', ['except' => 'show']);

/**
 * Dashboard
 */
Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->name('dashboard');

Route::fallback(function () {
    return view('errors.404');
})->name('fallback');

/**
 * Images
 */
Route::resource('images', 'ImageController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);

/**
 * Current LAN
 */
Route::get('/info', 'CurrentLanController@info')
    ->name('info');
Route::get('/events', 'CurrentLanController@events')
    ->name('events');
Route::get('/users', 'CurrentLanController@users')
    ->name('users');
