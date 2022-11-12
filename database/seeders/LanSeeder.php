<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Venue;

class LanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (Lan::count()) {
            return;
        }

        Lan::create(
            [
                'venue_id' => Venue::first()->id,
                'name' => __('seeder.example-lan'),
                'start' => Carbon::today()->addHours(18),
                'end' => Carbon::today()->addDays(2)->addHours(18),
                'published' => true,
            ]
        );
    }
}
