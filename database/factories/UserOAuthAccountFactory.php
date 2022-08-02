<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserOAuthAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName(),
            'provider' => $this->faker->domainWord(),
            'provider_id' => $this->faker->randomNumber(5),
            'avatar' => $this->faker->imageUrl(32, 32),
            'access_token' => $this->faker->md5(),
            'token_expiry' => $this->faker->dateTimeThisYear(),
            'refresh_token' => $this->faker->md5(),
        ];
    }
}
