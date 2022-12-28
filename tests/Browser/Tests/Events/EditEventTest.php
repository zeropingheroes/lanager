<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EditEventTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testEditingEvent(): void
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

            // And the LAN has an event
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
            ]);

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // And clicks the "options" dropdown next to the event
            $browser->clickAtXPath('//a[text()="' . $event->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And waits for the "edit event" page to load
            $browser->waitForRoute('lans.events.edit', ['lan' => $lan, 'event' => $event->id]);

            // And updates the event's name
            $browser->on(new EventEdit());
            $browser->type('name', 'My Edited Event');

            // And submits the "edit event" form
            $browser->press('@submit');

            // Then they should be redirected to the event's page
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event->id]);

            // And they should see the event's new name
            $browser->assertSee('My Edited Event');
        });
    }
}
