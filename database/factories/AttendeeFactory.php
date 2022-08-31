<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lan_id' => Lan::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
