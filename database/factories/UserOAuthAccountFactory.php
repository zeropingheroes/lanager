<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\UserOAuthAccount;

$factory->define(UserOAuthAccount::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'provider' => $faker->domainWord,
        'provider_id' => $faker->randomNumber(5),
        'avatar' => $faker->imageUrl(32, 32),
        'access_token' => $faker->md5,
        'token_expiry' => $faker->dateTimeThisYear,
        'refresh_token' => $faker->md5,
    ];
});
