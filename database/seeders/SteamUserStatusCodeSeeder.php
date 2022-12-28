<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\Models\SteamUserStatusCode;

class SteamUserStatusCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (SteamUserStatusCode::count()) {
            return;
        }

        $statusCodes = [
            [
                'id' => 0,
                'name' => 'offline',
                'display_name' => __('seeder.offline'),
            ],
            [
                'id' => 1,
                'name' => 'online',
                'display_name' => __('seeder.online'),
            ],
            [
                'id' => 2,
                'name' => 'busy',
                'display_name' => __('seeder.busy'),
            ],
            [
                'id' => 3,
                'name' => 'away',
                'display_name' => __('seeder.away'),
            ],
            [
                'id' => 4,
                'name' => 'snooze',
                'display_name' => __('seeder.snooze'),
            ],
            [
                'id' => 5,
                'name' => 'looking-to-trade',
                'display_name' => __('seeder.looking-to-trade'),
            ],
            [
                'id' => 6,
                'name' => 'looking-to-play',
                'display_name' => __('seeder.looking-to-play'),
            ],
        ];

        foreach ($statusCodes as $statusCode) {
            SteamUserStatusCode::create($statusCode);
        }
    }
}
