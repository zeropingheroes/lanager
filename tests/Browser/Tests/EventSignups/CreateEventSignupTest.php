<?php

namespace Tests\Browser\Tests\EventSignups;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventShow;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateEventSignupTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingEventSignup()
    {
        $this->browse(function (Browser $browser) {
            // Given a LAN exists
            // And the LAN started a few hours ago
            // And the LAN ends tomorrow
            // And the LAN is published
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => now()->subtract('hour', 4),
                'end' => now()->addDay(),
                'published' => true,
            ]);

            // And that LAN has an event
            // And the event starts in a few hours
            // And the event's sign-ups opened an hour ago
            // And the event's sign-ups close in a few hours
            // And the event is published
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My Event',
                'start' => now()->addHours(4),
                'end' => now()->addHours(6),
                'signups_open' => now()->subtract('hour', 1),
                'signups_close' => now()->add('hour', 4),
                'published' => true,
            ]);
            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And the user is logged in
            $browser->loginAs($user);

            // When the user visits the event page
            // And clicks the signup button
            // Then their name shows on the list of people who have signed up
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event])
                ->on(new EventShow())
                ->press('Sign up')
                ->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event])
                ->assertSee($user->username);
        });
    }
}
