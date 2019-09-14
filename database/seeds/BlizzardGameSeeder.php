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
        // don't seed if table is not empty
        if (BlizzardGame::count()) {
            return;
        }

        $games = [
            [
                'id' => 1,
                'name' => 'Call of Duty: Black Ops 4',
                'url' => 'https://shop.battle.net/product/call-of-duty-black-ops-4'
            ],
            [
                'id' => 2,
                'name' => 'Call of Duty: Modern Warfare',
                'url' => 'https://shop.battle.net/family/call-of-duty-mw'
            ],
            [
                'id' => 5,
                'name' => 'Diablo II',
                'url' => 'https://www.blizzard.com/games/d2/'
            ],
            [
                'id' => 6,
                'name' => 'Diablo III',
                'url' => 'https://shop.battle.net/family/diablo-iii'
            ],
            [
                'id' => 7,
                'name' => 'Hearthstone',
                'url' => 'https://shop.battle.net/family/hearthstone'
            ],
            [
                'id' => 8,
                'name' => 'Heroes of the Storm',
                'url' => 'https://shop.battle.net/family/heroes-of-the-storm'
            ],
            [
                'id' => 9,
                'name' => 'Overwatch',
                'url' => 'https://shop.battle.net/family/overwatch'
            ],
            [
                'id' => 10,
                'name' => 'StarCraft',
                'url' => 'https://shop.battle.net/product/starcraft'
            ],
            [
                'id' => 11,
                'name' => 'StarCraft II: Wings of Liberty',
                'url' => 'https://shop.battle.net/family/starcraft-ii'
            ],
            [
                'id' => 14,
                'name' => 'Warcraft III: Reign of Chaos',
                'url' => 'https://www.blizzard.com/en-gb/games/war3/'
            ],
            [
                'id' => 15,
                'name' => 'World of Warcraft',
                'url' => 'https://shop.battle.net/family/world-of-warcraft'
            ],
        ];

        foreach($games as $game) {
            BlizzardGame::create($game);
        }
    }
}
