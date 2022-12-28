<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\Models\Venue;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (Venue::count()) {
            return;
        }

        Venue::create(
            [
            'name' => __('seeder.example-venue'),
            'street_address' => 'Elmiavägen 15, 554 54 Jönköping, Sweden',
            'description' => __('seeder.example-venue-description'),
            ]
        );
    }
}
