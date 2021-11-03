<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Venue;

$factory->define(Lan::class, function (Faker $faker) {
    $start = $faker->dateTimeThisDecade;
    $end = $faker->dateTimeBetween($start, '+ 3 days');

    return [
        'name' => $faker->company,
        'description' => $faker->realText(100),
        'start' => $start,
        'end' => $end,
        'published' => $faker->boolean,
    ];
});
