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
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the LAN index page
            $browser->visit(new LanIndex());

            // And clicks the "create" button
            $browser->click('@create');

            // And waits for the "create LAN" page to load
            $browser->waitForRoute('lans.create');

            // And fills the "create LAN" form
            $browser->on(new LanCreate());
            $browser->type('name', 'My Great LAN');
            $browser->type('start', '2022-09-23 18:00');
            $browser->type('end', '2022-09-25 18:00');

            // And submits the form
            $browser->press('@submit');

            // Then they should be redirected to the LAN's event index page
            $browser->assertRouteIs('lans.events.index', '*');

            // And they should see the event's name
            $browser->assertSee('My Great LAN');
        });
    }
}
