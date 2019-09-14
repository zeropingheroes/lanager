<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\BlizzardGame;
use Zeropingheroes\Lanager\LanAttendeeGamePick;

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
            1 => [
                'name' => 'Call of Duty: Black Ops 4',
                'url' => 'https://shop.battle.net/product/call-of-duty-black-ops-4'
            ],
            2 => [
                'name' => 'Call of Duty: Modern Warfare',
                'url' => 'https://shop.battle.net/family/call-of-duty-mw'
            ],
            5 => [
                'name' => 'Diablo II',
                'url' => 'https://www.blizzard.com/games/d2/'
            ],
            6 => [
                'name' => 'Diablo III',
                'url' => 'https://shop.battle.net/family/diablo-iii'
            ],
            7 => [
                'name' => 'Hearthstone',
                'url' => 'https://shop.battle.net/family/hearthstone'
            ],
            8 => [
                'name' => 'Heroes of the Storm',
                'url' => 'https://shop.battle.net/family/heroes-of-the-storm'
            ],
            9 => [
                'name' => 'Overwatch',
                'url' => 'https://shop.battle.net/family/overwatch'
            ],
            10 => [
                'name' => 'StarCraft',
                'url' => 'https://shop.battle.net/product/starcraft'
            ],
            11 => [
                'name' => 'StarCraft II',
                'url' => 'https://shop.battle.net/family/starcraft-ii'
            ],
            14 => [
                'name' => 'Warcraft III: Reign of Chaos',
                'url' => 'https://www.blizzard.com/en-gb/games/war3/'
            ],
            15 => [
                'name' => 'World of Warcraft',
                'url' => 'https://shop.battle.net/family/world-of-warcraft'
            ],
        ];

        foreach ($games as $gameId => $game) {
            BlizzardGame::updateOrCreate(['id' => $gameId], $game);
        }

        // Delete games and game picks from the database that don't exist in the seeder
        $databaseIds = BlizzardGame::all()->pluck('id')->toArray();
        foreach ($databaseIds as $databaseId) {
            if (!array_key_exists($databaseId, $games)) {
                LanAttendeeGamePick::where(
                    [
                        ['game_id_type', '=', 'blizzard'],
                        ['game_id', '=', $databaseId]
                    ]
                )->delete();
                BlizzardGame::destroy($databaseId);
            }
        }
    }
}
