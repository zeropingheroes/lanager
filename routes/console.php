<?php

use Illuminate\Support\Facades\Schedule;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamApps;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamUserApps;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamUsers;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// The Steam Web API limits requests to 100,000 per day
// the below schedules will not exceed this limit for a LAN party of ~1,000 users.

// 1 Steam Web API request per 100 users
// Example for 1000 users:
// 10 requests each minute * 1440 minutes in a day = 14,400 daily requests
Schedule::command(UpdateSteamUsers::class)
    ->everyMinute();

// 1 Steam Web API request per user
// Example for 1000 users:
// 1000 requests * 48 half-ours in a day = 48,000 daily requests
Schedule::command(UpdateSteamUserApps::class)
    ->everyThirtyMinutes();

// 1 Steam Web API request per 50,000 Steam apps
// Example for ~160,000 steam apps:
// 4 requests * 24 hours = 96 daily requests
Schedule::command(UpdateSteamApps::class)
    ->hourly();
