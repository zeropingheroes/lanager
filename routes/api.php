<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::name('api.')->group(function () {
    Route::get('users/me', 'Api\UserController@showAuth')->middleware('session');
    Route::resource('users', 'Api\UserController', ['only' => ['index', 'show']]);
    Route::resource('lans', 'Api\LanController', ['only' => ['index', 'show']]);
    Route::resource('lans.slides', 'Api\SlideController', ['only' => ['index', 'show']]);
    Route::resource('events', 'Api\EventController', ['only' => ['index', 'show']]);
    Route::resource('active-games', 'Api\ActiveGamesController', ['only' => ['index']]);
});

Route::fallback(function () {
    return response()->json(['error' => ['message' => __('http-status-codes.404-title')]],404);
})->name('fallback');