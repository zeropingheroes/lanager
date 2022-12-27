<?php

namespace Tests\Browser\Tests\LanGames;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class IndexLanGameTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testIndexingLanGame()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And a superadmin exists
            $this->createSuperAdmin();

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And the user is logged in
            $browser->loginAs($user);

            // And the user has submitted several games they would like to play
            $lanGame = LanGame::create([
                'lan_id' => $lan->id,
                'game_name' => 'Apex Legends',
                'created_by' => $user->id,
            ]);
            $lanGame = LanGame::create([
                'lan_id' => $lan->id,
                'game_name' => 'PUBG',
                'created_by' => $user->id,
            ]);
            $lanGame = LanGame::create([
                'lan_id' => $lan->id,
                'game_name' => 'Smallworld',
                'created_by' => $user->id,
            ]);

            // When the user visits the LAN's games page
            $browser->visitRoute('lans.lan-games.index', ['lan' => $lan]);

            // Then they should see each of the LAN games in the table
            foreach (LanGame::where(['lan_id' => $lan->id])->get() as $lanGame) {
                $browser->assertSeeIn('table', $lanGame->game_name);
            }
        });
    }
}
