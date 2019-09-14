<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\EpicGame;
use Zeropingheroes\Lanager\LanAttendeeGamePick;

class EpicGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            1 => ['name' => 'ABZU', 'url' => 'https://www.epicgames.com/store/product/abzu/home'],
            2 => ['name' => 'Afterparty', 'url' => 'https://www.epicgames.com/store/product/afterparty/home'],
            3 => ['name' => 'Alan Wake', 'url' => 'https://www.epicgames.com/store/product/alan-wake/home'],
            4 => ['name' => 'Ancestors The Humankind Odyssey', 'url' => 'https://www.epicgames.com/store/product/ancestors/home'],
            5 => ['name' => 'Anno 1800', 'url' => 'https://www.epicgames.com/store/product/anno-1800/home'],
            6 => ['name' => 'Ashen', 'url' => 'https://www.epicgames.com/store/product/ashen/home'],
            7 => ['name' => 'Atomicrops', 'url' => 'https://www.epicgames.com/store/product/atomicrops/home'],
            8 => ['name' => 'Auto Chess', 'url' => 'https://www.epicgames.com/store/product/auto-chess/home'],
            9 => ['name' => 'Axiom Verge', 'url' => 'https://www.epicgames.com/store/product/axiom-verge/home'],
            10 => ['name' => 'Beyond: Two Souls', 'url' => 'https://www.epicgames.com/store/product/beyond-two-souls/home'],
            11 => ['name' => 'Borderlands 3', 'url' => 'https://www.epicgames.com/store/product/borderlands-3/home'],
            12 => ['name' => 'Celeste', 'url' => 'https://www.epicgames.com/store/product/celeste/home'],
            13 => ['name' => 'City of Brass', 'url' => 'https://www.epicgames.com/store/product/city-of-brass/home'],
            14 => ['name' => 'Close to the Sun', 'url' => 'https://www.epicgames.com/store/product/close-to-the-sun/home'],
            15 => ['name' => 'Conarium', 'url' => 'https://www.epicgames.com/store/product/conarium/home'],
            16 => ['name' => 'Control', 'url' => 'https://www.epicgames.com/store/product/control/home'],
            17 => ['name' => 'Cyberpunk 2077', 'url' => 'https://www.epicgames.com/store/product/cyberpunk-2077/home'],
            18 => ['name' => 'Dangerous Driving', 'url' => 'https://www.epicgames.com/store/product/dangerous-driving/home'],
            19 => ['name' => 'Darksiders III', 'url' => 'https://www.epicgames.com/store/product/darksiders3/home'],
            20 => ['name' => 'Dauntless', 'url' => 'https://www.epicgames.com/store/product/dauntless/home'],
            21 => ['name' => 'Detroit: Become Human', 'url' => 'https://www.epicgames.com/store/product/detroit-become-human/home'],
            22 => ['name' => 'Donut County', 'url' => 'https://www.epicgames.com/store/product/donut-county/home'],
            23 => ['name' => 'Enter The Gungeon', 'url' => 'https://www.epicgames.com/store/product/enter-the-gungeon/home'],
            24 => ['name' => 'Falcon Age', 'url' => 'https://www.epicgames.com/store/product/falcon-age/home'],
            25 => ['name' => 'Far Cry Primal', 'url' => 'https://www.epicgames.com/store/product/far-cry-primal/home'],
            26 => ['name' => 'Fez', 'url' => 'https://www.epicgames.com/store/product/fez/home'],
            27 => ['name' => 'Flower', 'url' => 'https://www.epicgames.com/store/product/flower/home'],
            28 => ['name' => 'For Honor', 'url' => 'https://www.epicgames.com/store/product/for-honor/home'],
            29 => ['name' => 'Fortnite Battle Royale', 'url' => 'https://www.epicgames.com/store/product/fortnite/home'],
            30 => ['name' => 'Genesis Alpha One', 'url' => 'https://www.epicgames.com/store/product/genesis-alpha-one/home'],
            31 => ['name' => 'Ghostbusters: The Video Game Remastered', 'url' => 'https://www.epicgames.com/store/product/ghostbusters-the-video-game-remastered/home'],
            32 => ['name' => 'GNOG', 'url' => 'https://www.epicgames.com/store/product/gnog/home'],
            33 => ['name' => 'Gods & Monsters', 'url' => 'https://www.epicgames.com/store/product/gods-and-monsters/home'],
            34 => ['name' => 'Gorogoa', 'url' => 'https://www.epicgames.com/store/product/gorogoa/home'],
            35 => ['name' => 'Griftlands', 'url' => 'https://www.epicgames.com/store/product/griftlands/home'],
            36 => ['name' => 'Hades', 'url' => 'https://www.epicgames.com/store/product/hades/home'],
            37 => ['name' => 'Heavy Rain', 'url' => 'https://www.epicgames.com/store/product/heavy-rain/home'],
            38 => ['name' => 'Hello Neighbor Hide & Seek', 'url' => 'https://www.epicgames.com/store/product/hello-neighbor-hide-and-seek/home'],
            39 => ['name' => 'Hyper Light Drifter', 'url' => 'https://www.epicgames.com/store/product/hyper-light-drifter/home'],
            40 => ['name' => 'Inside', 'url' => 'https://www.epicgames.com/store/product/inside/home'],
            41 => ['name' => 'John Wick Hex', 'url' => 'https://www.epicgames.com/store/product/johnwickhex/home'],
            42 => ['name' => 'Journey', 'url' => 'https://www.epicgames.com/store/product/journey/home'],
            43 => ['name' => 'Journey To The Savage Planet', 'url' => 'https://www.epicgames.com/store/product/journey-to-the-savage-planet/home'],
            44 => ['name' => 'Kine', 'url' => 'https://www.epicgames.com/store/product/kine/home'],
            45 => ['name' => 'Kingdom New Lands', 'url' => 'https://www.epicgames.com/store/product/kingdom-new-lands/home'],
            46 => ['name' => 'Last Day of June', 'url' => 'https://www.epicgames.com/store/product/last-day-of-june/home'],
            47 => ['name' => 'Limbo', 'url' => 'https://www.epicgames.com/store/product/limbo/home'],
            48 => ['name' => 'Maneater', 'url' => 'https://www.epicgames.com/store/product/maneater/home'],
            49 => ['name' => 'Mechwarrior 5', 'url' => 'https://www.epicgames.com/store/product/mechwarrior-5/home'],
            50 => ['name' => 'Metro Exodus', 'url' => 'https://www.epicgames.com/store/product/metro-exodus/home'],
            51 => ['name' => 'Metro: Last Light Redux', 'url' => 'https://www.epicgames.com/store/product/metro-last-light-redux/home'],
            52 => ['name' => 'Moonlighter', 'url' => 'https://www.epicgames.com/store/product/moonlighter/home'],
            53 => ['name' => 'Mutant Year Zero: Road To Eden', 'url' => 'https://www.epicgames.com/store/product/mutant-year-zero/home'],
            54 => ['name' => 'My Time at Portia', 'url' => 'https://www.epicgames.com/store/product/my-time-at-portia/home'],
            55 => ['name' => 'No Straight Roads', 'url' => 'https://www.epicgames.com/store/product/no-straight-roads/home'],
            56 => ['name' => 'Observation', 'url' => 'https://www.epicgames.com/store/product/observation/home'],
            57 => ['name' => 'Omen of Sorrow', 'url' => 'https://www.epicgames.com/store/product/omen-of-sorrow/home'],
            58 => ['name' => 'Operencia: The Stolen Sun', 'url' => 'https://www.epicgames.com/store/product/operencia/home'],
            59 => ['name' => 'Outer Wilds', 'url' => 'https://www.epicgames.com/store/product/outerwilds/home'],
            60 => ['name' => 'Outward', 'url' => 'https://www.epicgames.com/store/product/outward/home'],
            61 => ['name' => 'Overcooked', 'url' => 'https://www.epicgames.com/store/product/overcooked/home'],
            62 => ['name' => 'Oxenfree', 'url' => 'https://www.epicgames.com/store/product/oxenfree/home'],
            63 => ['name' => 'Oxygen Not Included', 'url' => 'https://www.epicgames.com/store/product/oxygen-not-included/home'],
            64 => ['name' => 'Phoenix Point', 'url' => 'https://www.epicgames.com/store/product/phoenix-point/home'],
            65 => ['name' => 'Rainbow Six Siege', 'url' => 'https://www.epicgames.com/store/product/rainbow-six-siege/home'],
            66 => ['name' => 'ReadySet Heroes', 'url' => 'https://www.epicgames.com/store/product/readyset-heroes/home'],
            67 => ['name' => 'Rebel Galaxy', 'url' => 'https://www.epicgames.com/store/product/rebel-galaxy/home'],
            68 => ['name' => 'Rebel Galaxy Outlaw', 'url' => 'https://www.epicgames.com/store/product/rebel-galaxy-outlaw/home'],
            69 => ['name' => 'RiME', 'url' => 'https://www.epicgames.com/store/product/rime/home'],
            70 => ['name' => 'RollerCoaster Tycoon Adventures', 'url' => 'https://www.epicgames.com/store/product/rollercoaster-tycoon-adventures/home'],
            71 => ['name' => 'Rune II', 'url' => 'https://www.epicgames.com/store/product/rune-2/home'],
            72 => ['name' => 'Satisfactory', 'url' => 'https://www.epicgames.com/store/product/satisfactory/home'],
            73 => ['name' => 'Shadow Complex Remastered', 'url' => 'https://www.epicgames.com/store/product/shadow-complex/home'],
            74 => ['name' => 'Shakedown Hawaii', 'url' => 'https://www.epicgames.com/store/product/shakedown-hawaii/home'],
            75 => ['name' => 'Shenmue III', 'url' => 'https://www.epicgames.com/store/product/shenmue-3/home'],
            76 => ['name' => 'Sherlock Holmes The Devil\'s Daughter', 'url' => 'https://www.epicgames.com/store/product/sherlock-holmes-the-devils-daughter/home'],
            77 => ['name' => 'Slime Rancher', 'url' => 'https://www.epicgames.com/store/product/slime-rancher/home'],
            78 => ['name' => 'Spellbreak', 'url' => 'https://www.epicgames.com/store/product/spellbreak/home'],
            79 => ['name' => 'Stories Untold', 'url' => 'https://www.epicgames.com/store/product/stories-untold/home'],
            80 => ['name' => 'Subnautica', 'url' => 'https://www.epicgames.com/store/product/subnautica/home'],
            81 => ['name' => 'Subnautica Below Zero', 'url' => 'https://www.epicgames.com/store/product/subnautica-below-zero/home'],
            82 => ['name' => 'Super Meat Boy', 'url' => 'https://www.epicgames.com/store/product/super-meat-boy/home'],
            83 => ['name' => 'Tetris® Effect', 'url' => 'https://www.epicgames.com/store/product/tetris-effect/home'],
            84 => ['name' => 'The Cycle', 'url' => 'https://www.epicgames.com/store/product/thecycle/home'],
            85 => ['name' => 'The Division 2', 'url' => 'https://www.epicgames.com/store/product/the-division-2/home'],
            86 => ['name' => 'The End is Nigh', 'url' => 'https://www.epicgames.com/store/product/the-end-is-nigh/home'],
            87 => ['name' => 'The Outer Worlds', 'url' => 'https://www.epicgames.com/store/product/the-outer-worlds/home'],
            88 => ['name' => 'The Settlers', 'url' => 'https://www.epicgames.com/store/product/the-settlers/home'],
            89 => ['name' => 'The Sinking City', 'url' => 'https://www.epicgames.com/store/product/the-sinking-city/home'],
            90 => ['name' => 'The Sojourn', 'url' => 'https://www.epicgames.com/store/product/the-sojourn/home'],
            91 => ['name' => 'The Walking Dead: The Final Season', 'url' => 'https://www.epicgames.com/store/product/walking-dead-final-season/home'],
            92 => ['name' => 'The Walking Dead: The Telltale Definitive Series', 'url' => 'https://www.epicgames.com/store/product/walking-dead-definitive-series/home'],
            93 => ['name' => 'The Witness', 'url' => 'https://www.epicgames.com/store/product/the-witness/home'],
            94 => ['name' => 'Thimbleweed Park', 'url' => 'https://www.epicgames.com/store/product/thimbleweed-park/home'],
            95 => ['name' => 'This War of Mine', 'url' => 'https://www.epicgames.com/store/product/this-war-of-mine/home'],
            96 => ['name' => 'Tom Clancy\'s Ghost Recon Breakpoint', 'url' => 'https://www.epicgames.com/store/product/ghost-recon-breakpoint/home'],
            97 => ['name' => 'Tom Clancy\'s Ghost Recon Wildlands', 'url' => 'https://www.epicgames.com/store/product/ghost-recon-wildlands/home'],
            98 => ['name' => 'Torchlight', 'url' => 'https://www.epicgames.com/store/product/torchlight/home'],
            99 => ['name' => 'Transistor', 'url' => 'https://www.epicgames.com/store/product/transistor/home'],
            100 => ['name' => 'Trials Rising', 'url' => 'https://www.epicgames.com/store/product/trials-rising/home'],
            101 => ['name' => 'Trover Saves the Universe', 'url' => 'https://www.epicgames.com/store/product/trover-saves-the-universe/home'],
            102 => ['name' => 'Unreal Tournament', 'url' => 'https://www.epicgames.com/store/product/unreal-tournament/home'],
            103 => ['name' => 'Vampire: The Masquerade – Bloodlines 2', 'url' => 'https://www.epicgames.com/store/product/vampire-the-masquerade-bloodlines-2/home'],
            104 => ['name' => 'Vampyr', 'url' => 'https://www.epicgames.com/store/product/vampyr/home'],
            105 => ['name' => 'Watch Dogs 2', 'url' => 'https://www.epicgames.com/store/product/watch-dogs-2/home'],
            106 => ['name' => 'Watch Dogs: Legion', 'url' => 'https://www.epicgames.com/store/product/watch-dogs-legion/home'],
            107 => ['name' => 'What Remains of Edith Finch', 'url' => 'https://www.epicgames.com/store/product/what-remains-of-edith-finch/home'],
            108 => ['name' => 'World of Goo', 'url' => 'https://www.epicgames.com/store/product/world-of-goo/home'],
            109 => ['name' => 'World War Z', 'url' => 'https://www.epicgames.com/store/product/world-war-z/home'],
            110 => ['name' => 'WRC 8 FIA World Rally Championship', 'url' => 'https://www.epicgames.com/store/product/wrc-8/home'],
        ];

        foreach ($games as $gameId => $game) {
            EpicGame::updateOrCreate(['id' => $gameId], $game);
        }

        // Delete games and game picks from the database that don't exist in the seeder
        $databaseIds = EpicGame::all()->pluck('id')->toArray();
        foreach ($databaseIds as $databaseId) {
            if (!array_key_exists($databaseId, $games)) {
                LanAttendeeGamePick::where(
                    [
                        ['game_id_type', '=', 'epic'],
                        ['game_id', '=', $databaseId]
                    ]
                )->delete();
                EpicGame::destroy($databaseId);
            }
        }
    }
}
