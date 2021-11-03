<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Zeropingheroes\Lanager\Attendee;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\LanGameVote;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;
use Zeropingheroes\Lanager\Venue;

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

        // Seed users & OAuth accounts
        factory(User::class, 100)->create()
            ->each(
                function ($user) {
                    $user->accounts()->save(factory(UserOAuthAccount::class)->make());
                }
            );

        // Seed venues & LANs
        factory(Venue::class, 10)->create()
            ->each(
                function ($venue) {
                    $venue->lans()->save(factory(Lan::class)->make());
                    $venue->lans()->save(factory(Lan::class)->make());
                    $venue->lans()->save(factory(Lan::class)->make());
                }
            );

        // Seed attendees
        factory(Attendee::class, 300)->create();

        // Seed LAN games
        factory(LanGame::class, 300)->create();

        // Seed LAN game votes
        factory(LanGameVote::class, 1000)->create();
    }
}
