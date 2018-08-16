<?php

/**
 * Current LAN
 */
Route::get('/', 'CurrentLanController@show')
    ->name('home');
Route::get('/guides', 'CurrentLanController@guides')
    ->name('guides');
Route::get('/events', 'CurrentLanController@events')
    ->name('events');
Route::get('/schedule', 'CurrentLanController@schedule')
    ->name('schedule');
Route::get('/users', 'CurrentLanController@users')
    ->name('users');

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
Route::get('/games/in-progress', 'GameController@inProgress')
    ->name('games.in-progress');
Route::get('/games/recent', 'GameController@recent')
    ->name('games.recent');
Route::get('/games/owned', 'GameController@owned')
    ->name('games.owned');

/**
 * LANs
 */
Route::resource('lans', 'LanController');

/**
 * Guides
 */
Route::resource('lans.guides', 'GuideController', ['except' => 'show']);
Route::get('lans/{lan}/guides/{guide}/{slug?}', 'GuideController@show')
    ->name('lans.guides.show');

/**
 * Events & Event Types
 */
Route::resource('lans.events', 'EventController');
Route::resource('event-types', 'EventTypeController');

/**
 * Users & Attendees
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
