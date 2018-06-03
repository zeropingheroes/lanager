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
    Route::resource('users', 'Api\UserController', ['only' => ['index', 'show']]);
    Route::resource('events', 'Api\EventController', ['only' => ['index', 'show']]);
});


Route::fallback(function () {
    return response()->json(['error' => ['message' => __('http-status-codes.404-title')]],404);
})->name('fallback');