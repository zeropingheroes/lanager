<?php

namespace Tests\Browser\Tests\EventSignups;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventShow;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventSignup;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class DeleteEventSignupTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingEventSignup(): void
    {
        $this->browse(function (Browser $browser) {
            // Given a LAN exists
            // And the LAN has an event
            $lan = Lan::factory()->has(
                Event::factory()
                    ->state(function (array $attributes, Lan $lan) {
                        return [
                            'lan_id' => $lan->id,
                            'start' => $lan->start->addHour(),
                            'end' => $lan->start->addHours(2),
                            'signups_open' => $lan->start,
                            'signups_close' => $lan->start->addHour(),
                            'published' => true,
                        ];
                    })
            )->count(1)->create()->first();

            // And a superadmin exists
            $superAdmin = $this->createSuperAdmin();

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And that user has signed up to the event
            $event = $lan->events->first();
            EventSignup::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ]);

            // And the user is logged in
            $browser->loginAs($user);

            // When the user goes to the event page
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // And clicks the delete signup button
            $browser->press('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // Then their name no longer shows on the list of people who have signed up
            $browser->assertDontSee($user->username);
        });
    }
}
