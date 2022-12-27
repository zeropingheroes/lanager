<?php

namespace Tests\Browser\Tests\LanGameVotes;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateLanGameVoteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingLanGameVote()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And a superadmin exists
            $superAdmin = $this->createSuperAdmin();

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And the user is logged in
            $browser->loginAs($user);

            // And the super admin has submitted a game they would like to play
            $lanGame = LanGame::create([
                'lan_id' => $lan->id,
                'game_name' => 'PUBG',
                'created_by' => $superAdmin->id,
            ]);

            // When the user visits the LAN's games page
            $browser->visitRoute('lans.lan-games.index', ['lan' => $lan]);

            // And clicks the text of the game the super admin submitted
            $browser->clickAtXPath('//label[contains(string(),"' . $lanGame->game_name . '")]');

            // Then they should see the checkbox next to the game they voted for is checked
            $browser->assertChecked('#lan_game_' . $lanGame->id . '_checkbox');
        });
    }
}
