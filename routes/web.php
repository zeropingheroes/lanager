<?php

/**
 * Home
 */
Route::get('/', function () {
    return redirect()->route('users.index');
})->name('home');

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
