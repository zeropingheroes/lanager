<?php

namespace Tests\Browser\Tests\Events;

use Illuminate\Support\Facades\Date;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventCreate;
use Tests\Browser\Pages\Events\EventIndex;
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
            $superAdmin = $this->createSuperAdmin();
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            $browser->loginAs($superAdmin)
                ->visitRoute('lans.events.index', ['lan' => $lan])
                ->on(new EventIndex())
                ->clickLink('Create')
                ->waitForRoute('lans.events.create', ['lan' => $lan])
                ->on(new EventCreate())
                ->type('name', 'My LAN Event')
                ->type('start', '2025-06-01 19:00')
                ->type('end', '2025-06-01 21:00')
                ->press('@submit')
                ->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => '*'])
                ->assertSee('My LAN Event');
        });
    }
}
