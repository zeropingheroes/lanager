<?php

/**
 * Web routes
 */

use Illuminate\Support\Facades\Route;
use Zeropingheroes\Lanager\Http\Controllers\AchievementController;
use Zeropingheroes\Lanager\Http\Controllers\AllowedIpRangeController;
use Zeropingheroes\Lanager\Http\Controllers\AttendeeController;
use Zeropingheroes\Lanager\Http\Controllers\AuthController;
use Zeropingheroes\Lanager\Http\Controllers\CurrentLanController;
use Zeropingheroes\Lanager\Http\Controllers\EventController;
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
    function () {
        return view('pages.games.fullscreen');
    }
)->name('games.fullscreen');

/**
 * LANs.
 */
Route::resource('lans', LanController::class);

/**
 * Guides.
 */
Route::resource('lans.guides', GuideController::class, ['except' => 'show']);
Route::get('lans/{lan}/guides/{guide}/{slug?}', [GuideController::class, 'show'])
    ->name('lans.guides.show');

/**
 * Events.
 */
Route::resource('lans.events', EventController::class);
Route::resource('lans.events.signups', EventSignupController::class, ['only' => ['store', 'destroy']]);
Route::get(
    '/events/fullscreen',
    function () {
        return view('pages.events.fullscreen');
    }
)->name('events.fullscreen');

/**
 * LAN Games & LAN Game Votes.
 */
Route::resource('lans.lan-games', LanGameController::class, ['except' => ['create']]);
Route::resource('lans.lan-games.votes', LanGameVoteController::class, ['only' => ['store', 'destroy']]);

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
    function (\Zeropingheroes\Lanager\Models\Lan $lan) {
        return view('pages.slides.play', ['lan' => $lan]);
    }
)->name('lans.slides.play');
Route::resource('lans.slides', SlideController::class);

/**
 * Allowed IP Ranges.
 */
Route::resource(
    'allowed-ip-ranges',
    AllowedIpRangeController::class,
    ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]
);

Route::fallback(
    function () {
        return view('errors.404');
    }
)->name('fallback');
