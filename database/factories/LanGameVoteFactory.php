<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\LanGameVote;
use Zeropingheroes\Lanager\User;

$factory->define(LanGameVote::class, function (Faker $faker) {
    return [
        'lan_game_id' => LanGame::all()->random()->id,
        'user_id' => User::all()->random()->id,
    ];
});
