<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SteamUserStatusCodeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(NavigationLinkSeeder::class);
        $this->call(VenueSeeder::class);
        $this->call(LanSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(AllowedIpRangeSeeder::class);
    }
}
