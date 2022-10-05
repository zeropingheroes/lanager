<?php

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanCreate;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;

class CreateLanTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingLan()
    {
        $superAdmin = $this->createSuperAdmin();

        $this->browse(function (Browser $browser) use ($superAdmin) {
            $browser->loginAs($superAdmin)
                ->visit(new LanIndex())
                ->click('@create')
                ->waitForRoute('lans.create')
                ->on(new LanCreate())
                ->type('name', 'My Great LAN')
                ->type('start', '2022-09-23 18:00')
                ->type('end', '2022-09-25 18:00')
                ->press('@submit')
                ->assertRouteIs('lans.events.index', '*')
                ->assertSee('My Great LAN');
        });
    }
}
