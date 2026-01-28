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

// Steam limit API calls to 100,000 per day
// the below schedules will not exceed this limit for a LAN party of ~1,000 users.

// 1 Steam API call per 100 users
// e.g. for 1000 users:
// 10 calls each minute * 1440 minutes in a day = 14,400 daily API calls
Schedule::command(UpdateSteamUsers::class)
    ->everyMinute();

// 1 Steam API call per user
// e.g. for 1000 users:
// 1000 calls * 48 half-ours in a day = 48,000 daily API calls
Schedule::command(UpdateSteamUserApps::class)
    ->everyThirtyMinutes();

// ~3 Steam API calls total
Schedule::command(UpdateSteamApps::class)
    ->hourly();
