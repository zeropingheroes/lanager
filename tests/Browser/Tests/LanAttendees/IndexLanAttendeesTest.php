<?php

namespace Tests\Browser\Tests\LanAttendees;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class IndexLanAttendeesTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingAchievements(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::factory()->count(1)->create()->first();

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And the user is attending the LAN
            $user->lans()->attach($lan->id);

            // When an unauthenticated user visits the LAN's attendees page
            $browser->visitRoute('lans.attendees.index', ['lan' => $lan]);

            // Then the user's name should show inside the table of attendees
            $browser->assertSeeIn('.table', $user->username);
        });
    }
}
