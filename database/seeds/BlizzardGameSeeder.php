<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\BlizzardGame;

class BlizzardGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            ['id' => 1, 'name' => 'Call of Duty: Black Ops 4'],
            ['id' => 2, 'name' => 'Call of Duty: Modern Warfare'],
            ['id' => 3, 'name' => 'Destiny 2'],
            ['id' => 4, 'name' => 'Diablo'],
            ['id' => 5, 'name' => 'Diablo II'],
            ['id' => 6, 'name' => 'Diablo III'],
            ['id' => 7, 'name' => 'Hearthstone'],
            ['id' => 8, 'name' => 'Heroes of the Storm'],
            ['id' => 9, 'name' => 'Overwatch'],
            ['id' => 10, 'name' => 'StarCraft'],
            ['id' => 11, 'name' => 'StarCraft II: Wings of Liberty'],
            ['id' => 12, 'name' => 'Warcraft: Orcs & Humans'],
            ['id' => 13, 'name' => 'Warcraft II: Tides of Darkness'],
            ['id' => 14, 'name' => 'Warcraft III: Reign of Chaos'],
            ['id' => 15, 'name' => 'World of Warcraft'],
        ];

        foreach($games as $game) {
            BlizzardGame::create($game);
        }
    }
}
