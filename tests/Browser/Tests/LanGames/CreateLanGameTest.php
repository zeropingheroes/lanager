<?php

namespace Tests\Browser\Tests\LanGames;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateLanGameTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingLanGame()
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

            // When the user visits the LAN's games page
            $browser->visitRoute('lans.lan-games.index', ['lan' => $lan]);

            // And types in the name of a game they want to play
            $browser->type('game_name', 'PUBG');

            // And clicks submit
            $browser->press('Submit');

            // Then they should see the name of the game in the list
            $browser->assertSeeIn('table', 'PUBG');
        });
    }
}
