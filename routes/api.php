<?php

use Illuminate\Support\Facades\Route;
use Zeropingheroes\Lanager\Http\Controllers\Api\ActiveGamesController;
use Zeropingheroes\Lanager\Http\Controllers\Api\EventController;
use Zeropingheroes\Lanager\Http\Controllers\Api\LanController;
use Zeropingheroes\Lanager\Http\Controllers\Api\SlideController;
use Zeropingheroes\Lanager\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::name('api.')->group(
    function () {
        Route::resource('users', UserController::class, ['only' => ['index', 'show']]);
        Route::resource('lans', LanController::class, ['only' => ['index', 'show']]);
        Route::resource('lans.slides', SlideController::class, ['only' => ['index', 'show']]);
        Route::resource('events', EventController::class, ['only' => ['index', 'show']]);
        Route::resource('active-games', ActiveGamesController::class, ['only' => ['index']]);
    }
);
