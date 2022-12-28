<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (Slide::count()) {
            return;
        }

        $lan_id = Lan::first()->id;

        $slides = [
            [
                'lan_id' => $lan_id,
                'name' => config('app.name'),
                'content' => __('seeder.slide-lanager', ['app-url' => config('app.url')]),
                'position' => 0,
                'duration' => 10,
                'published' => true,
            ],
            [
                'lan_id' => $lan_id,
                'name' => __('title.events'),
                'content' => url('/events/fullscreen'),
                'position' => 0,
                'duration' => 10,
                'published' => true,
            ],
            [
                'lan_id' => $lan_id,
                'name' => __('title.games'),
                'content' => url('/games/fullscreen'),
                'position' => 0,
                'duration' => 10,
                'published' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::create($slide);
        }
    }
}
