<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (EventType::count()) {
            return;
        }

        $eventTypes = [
            [
                'name' => __('seeder.ceremony'),
                'colour' => '#04c',
            ],
            [
                'name' => __('seeder.big-game'),
                'colour' => '#19A601', // light green
            ],
            [
                'name' => __('seeder.tournament'),
                'colour' => '#A0000C', // red
            ],
            [
                'name' => __('seeder.food-and-drink'),
                'colour' => '#d80',
            ],
            [
                'name' => __('seeder.projector'),
                'colour' => '#55f',
            ],

        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
