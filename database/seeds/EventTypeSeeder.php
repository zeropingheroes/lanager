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
                'name' => 'Ceremony',
                'colour' => '#04c',
            ],
            [
                'name' => 'Big Game',
                'colour' => '#19A601', // light green
            ],
            [
                'name' => 'Tournament',
                'colour' => '#A0000C', // red
            ],
            [
                'name' => 'Food & Drink',
                'colour' => '#d80',
            ],
            [
                'name' => 'Projector',
                'colour' => '#55f',
            ],

        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
