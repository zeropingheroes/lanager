<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Zeropingheroes\Lanager\Models\Attendee;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\LanGameVote;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\Lanager\Models\Venue;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed required data
        $this->call(DatabaseSeeder::class);

        // Import Steam apps
        Artisan::call('lanager:import-steam-apps-csv');

        // Seed users
        User::factory()
            ->has(
                UserOAuthAccount::factory()->count(1),
                'accounts'
            )
            ->count(100)->create();

        // Seed venues & LANs
        Venue::factory()
            ->has(
                Lan::factory()->count(5)
            )
            ->count(3)->create();

        // Seed attendees
        Attendee::factory()
            ->count(500)
            ->create();

        // Seed LAN games
        LanGame::factory()->count(200)->create();

        // Seed LAN game votes
        LanGameVote::factory()->count(1000)->create();
    }
}
