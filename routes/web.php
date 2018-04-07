<?php

/* Home */
Route::get('/', function () {
    return redirect()->route('users.index');
});

/* Auth */

Route::get('login', 'AuthController@showLoginForm')
    ->name('login');

Route::get('auth/{provider}', 'AuthController@redirectToProvider')
    ->name('auth');

Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback')
    ->name('auth.callback');

Route::post('logout', 'AuthController@logout')
    ->name('logout');

/* Users */

Route::resource('users', 'UserController', ['only' => ['index', 'show', 'destroy']]);
