<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::name('api.')->group(
    function () {
        Route::resource('users', 'Api\UserController', ['only' => ['index', 'show']]);
        Route::resource('lans', 'Api\LanController', ['only' => ['index', 'show']]);
        Route::resource('lans.slides', 'Api\SlideController', ['only' => ['index', 'show']]);
        Route::resource('events', 'Api\EventController', ['only' => ['index', 'show']]);
        Route::resource('active-games', 'Api\ActiveGamesController', ['only' => ['index']]);
    }
);
