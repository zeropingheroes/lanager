<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamApp;

class LanGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lan = Lan::all()->random();
        return [
            'lan_id' => $lan->id,
            'game_name' => SteamApp::where('type', '=', 'game')->inRandomOrder()->first()->name,
            'created_by' => $lan->users()->inRandomOrder()->first()->id,
        ];
    }
}
