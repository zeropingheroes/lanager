<?php

/**
 * Home
 */
Route::get('/', 'LanController@show')->name('home');

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
 * Users
 */
Route::resource('users', 'UserController', ['only' => ['index', 'show', 'destroy']]);

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
 * Info Pages
 */
Route::resource('pages', 'PageController', ['except' => 'show']);
Route::get('pages/{page}/{slug?}', 'PageController@show')
    ->name('pages.show');

/**
 * Navigation Links
 */
Route::resource('navigation-links', 'NavigationLinkController', ['except' => 'show']);

/**
 * LANs
 */
Route::resource('lans', 'LanController');

/**
 * Events & Event Types
 */
Route::resource('event-types', 'EventTypeController');
Route::resource('events', 'EventController');
Route::get('/schedule', function () {
    return view('pages.events.schedule');
})->name('schedule');

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
Route::resource('images', 'ImageController');