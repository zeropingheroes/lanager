<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\Attendee;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

$factory->define(Attendee::class, function (Faker $faker) {
    return [
        'lan_id' => Lan::all()->random()->id,
        'user_id' => User::all()->random()->id,
    ];
});
