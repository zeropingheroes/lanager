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
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the venue index page
            $browser->visit(new VenueIndex());

            // And clicks the "create" link
            $browser->click('@create');

            // And waits for the "create venue" page to load
            $browser->waitForRoute('venues.create');

            // And fills the "create venue" form
            $browser->on(new VenueCreate());
            $browser->type('name', 'My LAN Venue');
            $browser->type('street_address', '1 Example Road, Exampleton, Exampleland');

            // And submits the form
            $browser->press('@submit');

            // Then they should be redirected to the venue's page
            $browser->assertRouteIs('venues.show', '*');

            // And they should see the venue's name
            $browser->assertSee('My LAN Venue');

            // And they should see the venue's address
            $browser->assertSee('1 Example Road, Exampleton, Exampleland');
        });
    }
}
