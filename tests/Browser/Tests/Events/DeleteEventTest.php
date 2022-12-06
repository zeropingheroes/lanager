<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class DeleteEventTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingEvent(): void
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

            // And there is an event
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

            // And clicks the "options" dropdown next to the event's name in the table
            $browser->clickAtXPath('//a[text()="' . $event->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And accept the deletion confirmation dialog
            $browser->acceptDialog();

            // Then they should be redirected to the LAN's event index page
            $browser->assertRouteIs('lans.events.index', ['lan' => $lan]);

            // And they should see a confirmation message that the event has been deleted
            $browser->assertSee('Event "' . $event->name . '" deleted');
        });
    }
}
