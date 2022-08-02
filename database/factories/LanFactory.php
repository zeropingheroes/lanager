<?php

namespace Database\Factories;

use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeThisYear('+3 years');
        $end = clone $start;
        $end->add(new DateInterval('P' . rand(1, 7) . 'D'));

        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->realText(100),
            'start' => $start,
            'end' => $end,
            'published' => $this->faker->boolean(),
        ];
    }
}
