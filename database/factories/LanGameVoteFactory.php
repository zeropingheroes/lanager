<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\User;

class LanGameVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lan_game_id' => LanGame::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
