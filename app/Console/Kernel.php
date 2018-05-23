<?php

namespace Zeropingheroes\Lanager\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Zeropingheroes\Lanager\Console\Commands\SteamImportApps;
use Zeropingheroes\Lanager\Console\Commands\SteamImportUserApps;
use Zeropingheroes\Lanager\Console\Commands\SteamImportUsers;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
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
        $schedule->command(SteamImportUsers::class)
            ->everyMinute();

        // 1 Steam API call total
        $schedule->command(SteamImportApps::class)
            ->dailyAt('6:00');

        // 1 Steam API call per user
        // e.g. for 1000 users:
        // 1000 calls * 48 half-ours in a day = 48,000 daily API calls
        $schedule->command(SteamImportUserApps::class)
            ->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
