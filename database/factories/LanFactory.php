<?php

namespace Database\Factories;

use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeThisYear('+3 years');
        $end = clone $start;
        $end->add(new DateInterval('P' . rand(1, 7) . 'D'));

        return [
            'name' => $this->faker->company(),
            'start' => $start,
            'end' => $end,
            'published' => $this->faker->boolean(),
        ];
    }
}
