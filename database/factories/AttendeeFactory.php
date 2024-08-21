<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class AttendeeFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'lan_id' => Lan::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
