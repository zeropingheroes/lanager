<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Slide;

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

        Slide::create([
            'lan_id' => Lan::first()->id,
            'name' => config('app.name'),
            'content' => __('seeder.log-into-lanager', ['lanager-url' => config('app.url')]),
            'position' => 0,
            'duration' => 10,
            'published' => true,
        ]);
    }
}
