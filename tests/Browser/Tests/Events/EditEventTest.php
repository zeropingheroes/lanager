<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventEdit;
use Tests\Browser\Pages\Events\EventIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EditEventTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testEditingEvents(): void
    {
        $this->browse(function (Browser $browser) {
            $superAdmin = $this->createSuperAdmin();
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
            ]);

            $browser->loginAs($superAdmin)
                ->visitRoute('lans.events.index', ['lan' => $lan])
                ->on(new EventIndex())
                ->clickAtXPath('//a[text()="' . $event->name . '"]//..//..//button[@title="Options"]')
                ->clickLink('Edit')
                ->assertRouteIs('lans.events.edit', ['lan' => $lan, 'event' => $event->id])
                ->assertSee('Edit Event')
                ->on(new EventEdit())
                ->type('name', 'My Edited Event')
                ->press('@submit')
                ->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event->id])
                ->assertSee('My Edited Event');
        });
    }
}
