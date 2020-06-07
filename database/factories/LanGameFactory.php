<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\SteamApp;
use Zeropingheroes\Lanager\User;

$factory->define(LanGame::class, function (Faker $faker) {
    return [
        'lan_id' => Lan::all()->random()->id,
        'game_name' => SteamApp::where('type','=','game')->inRandomOrder()->first()->name,
        'created_by' => User::all()->random()->id,
    ];
});
