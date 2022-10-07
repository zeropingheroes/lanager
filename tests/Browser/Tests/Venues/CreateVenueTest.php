<?php

namespace Tests\Browser\Tests\Venues;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Venues\VenueCreate;
use Tests\Browser\Pages\Venues\VenueIndex;
use Tests\DuskTestCase;

class CreateVenueTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingVenue()
    {
        $superAdmin = $this->createSuperAdmin();

        $this->browse(function (Browser $browser) use ($superAdmin) {
            $browser->loginAs($superAdmin)
                ->visit(new VenueIndex())
                ->click('@create')
                ->waitForRoute('venues.create')
                ->on(new VenueCreate())
                ->type('name', 'My LAN Venue')
                ->type('street_address', '1 Example Road, Exampleton, Exampleland')
                ->press('@submit')
                ->screenshot('blah')
                ->assertRouteIs('venues.show', '*')
                ->assertSee('My LAN Venue')
                ->assertSee('1 Example Road, Exampleton, Exampleland');
        });
    }
}
