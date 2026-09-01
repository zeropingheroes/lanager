<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\LanAttendees;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class IndexLanAttendeesTest extends DuskTestCase
{
    public function test_indexing_achievements(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

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
