<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ottaviano\Faker\Gravatar;

class UserOAuthAccountFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Gravatar($this->faker));

        return [
            'username' => $this->faker->userName(),
            'provider' => $this->faker->domainWord(),
            'provider_id' => $this->faker->randomNumber(5),
            'avatar' => $this->faker->gravatarUrl(null, null, 32),
            'access_token' => $this->faker->md5(),
            'token_expiry' => $this->faker->dateTimeThisYear(),
            'refresh_token' => $this->faker->md5(),
        ];
    }
}
