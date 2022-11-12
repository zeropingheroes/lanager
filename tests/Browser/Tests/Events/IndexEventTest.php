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
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            $browser->visitRoute('lans.events.index', ['lan' => $lan])
                ->assertSee($event->name);
        });
    }
}
