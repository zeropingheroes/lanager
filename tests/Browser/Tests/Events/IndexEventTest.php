<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class IndexEventTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingEvents(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            // And it is published
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And the LAN has an event
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            // When an unauthenticated user visits the LAN's event list page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Then they should see the event's name
            $browser->assertSee($event->name);
        });
    }
}
