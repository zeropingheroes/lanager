<?php

declare(strict_types=1);

/**
 * Web routes
 */

use Illuminate\Support\Facades\Route;
use Zeropingheroes\Lanager\Http\Controllers\AchievementController;
use Zeropingheroes\Lanager\Http\Controllers\AllowedIpRangeController;
use Zeropingheroes\Lanager\Http\Controllers\ApiTokenController;
use Zeropingheroes\Lanager\Http\Controllers\AttendeeController;
use Zeropingheroes\Lanager\Http\Controllers\AuthController;
use Zeropingheroes\Lanager\Http\Controllers\CurrentLanController;
use Zeropingheroes\Lanager\Http\Controllers\DiscordChannelWebhookController;
use Zeropingheroes\Lanager\Http\Controllers\DiscordChannelWebhookMessageController;
use Zeropingheroes\Lanager\Http\Controllers\EventController;
use Zeropingheroes\Lanager\Http\Controllers\EventDiscordNotificationMessageController;
use Zeropingheroes\Lanager\Http\Controllers\EventSignupController;
use Zeropingheroes\Lanager\Http\Controllers\GameController;
use Zeropingheroes\Lanager\Http\Controllers\GuideController;
use Zeropingheroes\Lanager\Http\Controllers\ImageController;
use Zeropingheroes\Lanager\Http\Controllers\LanController;
use Zeropingheroes\Lanager\Http\Controllers\LanGameController;
use Zeropingheroes\Lanager\Http\Controllers\LanGameVoteController;
use Zeropingheroes\Lanager\Http\Controllers\NavigationLinkController;
use Zeropingheroes\Lanager\Http\Controllers\RoleAssignmentController;
use Zeropingheroes\Lanager\Http\Controllers\SlideController;
use Zeropingheroes\Lanager\Http\Controllers\UserAchievementController;
use Zeropingheroes\Lanager\Http\Controllers\UserController;
use Zeropingheroes\Lanager\Http\Controllers\VenueController;
use Zeropingheroes\Lanager\Models\Lan;

/**
 * Current LAN.
 */
Route::get('/', [CurrentLanController::class, 'show'])
    ->name('home');
Route::get('/guides', [CurrentLanController::class, 'guides'])
    ->name('guides');
Route::get('/events', [CurrentLanController::class, 'events'])
    ->name('events');
Route::get('/schedule', [CurrentLanController::class, 'schedule'])
    ->name('schedule');
Route::get('/users', [CurrentLanController::class, 'users'])
    ->name('users');
Route::get('/user-achievements', [CurrentLanController::class, 'userAchievements'])
    ->name('users.achievements');

/**
 * Login.
 */
Route::get('login', [AuthController::class, 'showLoginForm'])
    ->middleware(['guest'])
    ->name('login');

Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])
    ->middleware(['guest'])
    ->name('auth');

Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])
    ->middleware(['guest'])
    ->name('auth.callback');

/**
 * Logout.
 */
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');

/**
 * Roles & Role Assignments.
 */
Route::resource('role-assignments', RoleAssignmentController::class, ['except' => ['show', 'edit', 'update']]);

/**
 * API Tokens.
 */
Route::middleware(['auth'])->group(
    function (): void {
        Route::resource('api-tokens', ApiTokenController::class, ['only' => ['index', 'store', 'destroy']]);
    }
);

/**
 * Games.
 */
Route::get('/games/in-progress', [GameController::class, 'inProgress'])
    ->name('games.in-progress');
Route::get('/games/recent', [GameController::class, 'recent'])
    ->name('games.recent');
Route::get('/games/owned', [GameController::class, 'owned'])
    ->name('games.owned');
Route::get(
    '/games/fullscreen',
    fn () => view('pages.games.fullscreen')
)->name('games.fullscreen');

/**
 * LANs.
 */
Route::resource('lans', LanController::class);
Route::get('lans/{lan}/clone', [LanController::class, 'clone'])
    ->name('lans.clone.create');
Route::post('lans/{lan}/clone', [LanController::class, 'storeClone'])
    ->name('lans.clone.store');
Route::patch('lans/{lan}/publish', [LanController::class, 'publish'])
    ->name('lans.publish');
Route::patch('lans/{lan}/unpublish', [LanController::class, 'unpublish'])
    ->name('lans.unpublish');

/**
 * Guides.
 */
Route::resource('lans.guides', GuideController::class, ['except' => 'show']);
// Clone routes must be registered before the show route, otherwise {slug} would match "clone"
Route::get('lans/{lan}/guides/{guide}/clone', [GuideController::class, 'clone'])
    ->name('lans.guides.clone.create');
Route::post('lans/{lan}/guides/{guide}/clone', [GuideController::class, 'storeClone'])
    ->name('lans.guides.clone.store');
Route::get('lans/{lan}/guides/{guide}/{slug?}', [GuideController::class, 'show'])
    ->name('lans.guides.show');
Route::patch('lans/{lan}/guides/{guide}/publish', [GuideController::class, 'publish'])
    ->name('lans.guides.publish');
Route::patch('lans/{lan}/guides/{guide}/unpublish', [GuideController::class, 'unpublish'])
    ->name('lans.guides.unpublish');

/**
 * Discord Channel Webhooks.
 */
Route::resource('lans.discord-channel-webhooks', DiscordChannelWebhookController::class, ['only' => ['index', 'store', 'destroy']]);
Route::resource('lans.discord-channel-webhooks.messages', DiscordChannelWebhookMessageController::class, ['only' => ['create']]);

/**
 * Events.
 */
Route::get('/events/fullscreen', [CurrentLanController::class, 'eventsFullscreen'])
    ->name('events.fullscreen');
Route::get(
    'lans/{lan}/events/fullscreen',
    fn (Lan $lan) => view('pages.events.fullscreen')->with('lan', $lan)
)->name('lans.events.fullscreen');
Route::resource('lans.events', EventController::class);
Route::get('lans/{lan}/events/{event}/clone', [EventController::class, 'clone'])
    ->name('lans.events.clone.create');
Route::post('lans/{lan}/events/{event}/clone', [EventController::class, 'storeClone'])
    ->name('lans.events.clone.store');
Route::patch('lans/{lan}/events/{event}/publish', [EventController::class, 'publish'])
    ->name('lans.events.publish');
Route::patch('lans/{lan}/events/{event}/unpublish', [EventController::class, 'unpublish'])
    ->name('lans.events.unpublish');
Route::resource('lans.events.signups', EventSignupController::class, ['only' => ['store', 'destroy']])
    ->parameters(['signups' => 'eventSignup']);

/**
 * Event Discord Notification Messages.
 */
Route::singleton('lans.events.discord-notification-message', EventDiscordNotificationMessageController::class)
    ->creatable()
    ->destroyable()
    ->except(['show']);

/**
 * LAN Games & LAN Game Votes.
 */
Route::resource('lans.lan-games', LanGameController::class, ['except' => ['create']]);
Route::resource('lans.lan-games.votes', LanGameVoteController::class, ['only' => ['store', 'destroy']])
    ->parameters(['votes' => 'lanGameVote']);

/**
 * Users & Attendees.
 */
Route::resource('users', UserController::class, ['only' => ['show', 'destroy']]);
Route::resource('lans.attendees', AttendeeController::class, ['only' => ['index']]);

/**
 * Achievements.
 */
Route::resource('achievements', AchievementController::class);
Route::resource('lans.user-achievements', UserAchievementController::class, ['except' => ['show', 'edit', 'update']]);

/**
 * Navigation Links.
 */
Route::resource('navigation-links', NavigationLinkController::class, ['except' => 'show']);

/**
 * Images.
 */
Route::resource('images', ImageController::class, ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);

/**
 * Venues.
 */
Route::resource('venues', VenueController::class);

/**
 * Slides.
 */
Route::get(
    'lans/{lan}/slides/play',
    fn (Lan $lan) => view('pages.slides.play', ['lan' => $lan])
)->name('lans.slides.play');
Route::resource('lans.slides', SlideController::class);
Route::get('lans/{lan}/slides/{slide}/clone', [SlideController::class, 'clone'])
    ->name('lans.slides.clone.create');
Route::post('lans/{lan}/slides/{slide}/clone', [SlideController::class, 'storeClone'])
    ->name('lans.slides.clone.store');
Route::patch('lans/{lan}/slides/{slide}/publish', [SlideController::class, 'publish'])
    ->name('lans.slides.publish');
Route::patch('lans/{lan}/slides/{slide}/unpublish', [SlideController::class, 'unpublish'])
    ->name('lans.slides.unpublish');

/**
 * Allowed IP Ranges.
 */
Route::resource(
    'allowed-ip-ranges',
    AllowedIpRangeController::class,
    ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]
);

Route::fallback(
    fn () => view('errors.404')
)->name('fallback');
