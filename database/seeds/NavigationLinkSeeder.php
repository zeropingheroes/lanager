<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\NavigationLink;

class NavigationLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (NavigationLink::count()) {
            return;
        }

        $navigationLinks = [
            [
                'title' => 'Schedule',
                'position' => '1',
                'url' => '/schedule',
                'parent_id' => null,
            ],
            [
                'title' => 'Games',
                'position' => '2',
                'url' => '/games/in-progress',
                'parent_id' => null,
            ],
            [
                'title' => 'Attendees',
                'position' => '3',
                'url' => '/users',
                'parent_id' => null,
            ],
            [
                'title' => 'Achievements',
                'position' => '4',
                'url' => '/achievements',
                'parent_id' => null,
            ],
            [
                'title' => 'Guides',
                'position' => '5',
                'url' => '/guides',
                'parent_id' => null,
            ],
        ];

        foreach ($navigationLinks as $navigationLink) {
            NavigationLink::create($navigationLink);
        }
    }
}
