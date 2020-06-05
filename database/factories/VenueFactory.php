<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\Venue;

$factory->define(Venue::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'street_address' => $faker->address,
        'description' => $faker->realText(100),
    ];
});
