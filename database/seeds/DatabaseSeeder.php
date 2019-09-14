<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SteamUserStatusCodeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(NavigationLinkSeeder::class);
        $this->call(VenueSeeder::class);
        $this->call(LanSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(WhitelistedIpRangeSeeder::class);
        $this->call(BlizzardGameSeeder::class);
        $this->call(EpicGameSeeder::class);
    }
}
