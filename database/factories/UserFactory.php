<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'remember_token' => Str::random(10),
        ];
    }
}
