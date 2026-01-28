<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamApp;

class LanGameFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        $lan = Lan::all()->random();
        return [
            'lan_id' => $lan->id,
            'game_name' => SteamApp::inRandomOrder()->first()->name,
            'created_by' => $lan->users()->inRandomOrder()->first()->id,
        ];
    }
}
