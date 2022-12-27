<?php

namespace Tests\Browser\Tests\LanGames;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class EditLanGameTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEditingLanGame()
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

            // And the user has submitted a game they would like to play
            $lanGame = LanGame::create([
                'lan_id' => $lan->id,
                'game_name' => 'PUBG',
                'created_by' => $user->id,
            ]);

            // When the user visits the LAN's games page
            $browser->visitRoute('lans.lan-games.index', ['lan' => $lan]);

            // And clicks the delete link next to the game they have submitted
            $browser->clickAtXPath(
                '//label[contains(string(),"' . $lanGame->game_name . '")]//..//..//..//..//a[@title="Edit"]'
            );

            // And waits for the LAN game edit page to load
            $browser->waitForRoute('lans.lan-games.edit', ['lan' => $lan, 'lan_game' => $lanGame->id]);

            // And types in a new name for the game
            $browser->type('game_name', 'Apex Legends');

            // And clicks the submit button
            $browser->press('Submit');

            // And waits for the LAN games index page to load
            $browser->waitForRoute('lans.lan-games.index', ['lan' => $lan]);

            // Then they should not see the old game name in the games table
            $browser->assertDontSeeIn('table', $lanGame->game_name);

            // And they should see the new game name in the games table
            $browser->assertSeeIn('table', 'Apex Legends');
        });
    }
}
