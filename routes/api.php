<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Zeropingheroes\Lanager\Http\Controllers\Api\ActiveGamesController;
use Zeropingheroes\Lanager\Http\Controllers\Api\DiscordChannelWebhookMessageController;
use Zeropingheroes\Lanager\Http\Controllers\Api\EventController;
use Zeropingheroes\Lanager\Http\Controllers\Api\EventDiscordNotificationMessageDeliveryController;
use Zeropingheroes\Lanager\Http\Controllers\Api\EventDiscordNotificationMessagePreviewController;
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
    function (): void {
        Route::prefix('v1')->name('v1.')->group(
            function (): void {
                Route::resource('users', UserController::class, ['only' => ['index', 'show']]);
                Route::resource('lans', LanController::class, ['only' => ['index', 'show']]);
                Route::resource('lans.slides', SlideController::class, ['only' => ['index', 'show']]);
                Route::resource('events', EventController::class, ['only' => ['index', 'show']]);
                Route::resource('active-games', ActiveGamesController::class, ['only' => ['index']]);

                /*
                |----------------------------------------------------------------------
                | Discord notification actions.
                |
                | Unlike the rest of this file, these routes are authenticated via the
                | web session (not the stateless "api" group) and are state-changing:
                | they require an authenticated, authorized user and enforce the same
                | Policy checks as the rest of the app.
                |----------------------------------------------------------------------
                */
                Route::middleware('web')->group(
                    function (): void {
                        Route::post('events/{event}/discord-notification-message/deliveries', [EventDiscordNotificationMessageDeliveryController::class, 'store'])
                            ->name('events.discord-notification-message.deliveries.store');
                        Route::post('events/{event}/discord-notification-message/previews', [EventDiscordNotificationMessagePreviewController::class, 'store'])
                            ->name('events.discord-notification-message.previews.store');
                        Route::post('lans/{lan}/discord-channel-webhooks/{discordChannelWebhook}/messages', [DiscordChannelWebhookMessageController::class, 'store'])
                            ->name('lans.discord-channel-webhooks.messages.store');
                    }
                );
            }
        );

        Route::fallback(
            fn () => response()->json(['message' => 'Not Found'], 404)
        )->name('fallback');
    }
);
