<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\SteamUserStatusCode;

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
                'name' => 'Offline',
            ],
            [
                'id' => 1,
                'name' => 'Online',
            ],
            [
                'id' => 2,
                'name' => 'Busy',
            ],
            [
                'id' => 3,
                'name' => 'Away',
            ],
            [
                'id' => 4,
                'name' => 'Snooze',
            ],
            [
                'id' => 5,
                'name' => 'Looking to trade',
            ],
            [
                'id' => 6,
                'name' => 'Looking to play',
            ],

        ];

        foreach ($statusCodes as $statusCode) {
            SteamUserStatusCode::create($statusCode);
        }
    }
}
