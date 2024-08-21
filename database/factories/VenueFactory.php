<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'street_address' => $this->faker->address(),
            'description' => $this->faker->realText(100),
        ];
    }
}
