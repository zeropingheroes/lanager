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
                'title' => __('title.schedule'),
                'position' => '1',
                'url' => '/schedule',
                'parent_id' => null,
            ],
            [
                'title' => __('title.games'),
                'position' => '2',
                'url' => '/games/in-progress',
                'parent_id' => null,
            ],
            [
                'title' => __('title.attendees'),
                'position' => '3',
                'url' => '/users',
                'parent_id' => null,
            ],
            [
                'title' => __('title.achievements'),
                'position' => '4',
                'url' => '/user-achievements',
                'parent_id' => null,
            ],
            [
                'title' => __('title.guides'),
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
