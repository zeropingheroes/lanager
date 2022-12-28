<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventCreate;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class CreateEventTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingEvent()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // And clicks the "create" link
            $browser->clickLink('Create');

            // And waits for the "create event" page to show
            $browser->waitForRoute('lans.events.create', ['lan' => $lan]);

            // And fills the "create event" form
            $browser->on(new EventCreate());
            $browser->type('name', 'My LAN Event');
            $browser->type('start', '2025-06-01 19:00');
            $browser->type('end', '2025-06-01 21:00');

            // And submits the form
            $browser->press('@submit');

            // Then they should be on the event show page
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => '*']);

            // And they should see the event title
            $browser->assertSee('My LAN Event');
        });
    }
}
