<?php

namespace Zeropingheroes\Lanager\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamApps;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamUserApps;
use Zeropingheroes\Lanager\Console\Commands\UpdateSteamUsers;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Commands that query Steam API.
        // Steam limit API calls to 100,000 per day
        // and the below schedules will not exceed this limit
        // for a LAN party of ~1,000 users.

        // 1 Steam API call per 100 users
        // e.g. for 1000 users:
        // 10 calls each minute * 1440 minutes in a day = 14,400 daily API calls
        $schedule->command(UpdateSteamUsers::class)
            ->everyMinute();

        // 1 Steam API call per user
        // e.g. for 1000 users:
        // 1000 calls * 48 half-ours in a day = 48,000 daily API calls
        $schedule->command(UpdateSteamUserApps::class)
            ->everyThirtyMinutes();

        // 1 Steam API call total
        $schedule->command(UpdateSteamApps::class)
            ->hourly();

        // Many rate-limited Steam API calls
        $schedule->command(UpdateSteamAppsMetadata::class)
            ->twiceDaily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        /** @noinspection PhpIncludeInspection */
        require base_path('routes/console.php');
    }
}
