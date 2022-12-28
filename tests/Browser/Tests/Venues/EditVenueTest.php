<?php

namespace Tests\Browser\Tests\Venues;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Venues\VenueEdit;
use Tests\Browser\Pages\Venues\VenueIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Venue;

class EditVenueTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testEditingVenue(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a venue
            $venue = Venue::factory()->count(1)->create()->first();

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the venue index page
            $browser->visit(new VenueIndex());

            // And clicks the "options" dropdown next to the venue's name
            $browser->clickAtXPath('//a[text()="' . $venue->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And waits for the "edit venue" page to load
            $browser->waitForRoute('venues.edit', ['venue' => $venue->id]);

            // And updates the field for the venue's name
            $browser->on(new VenueEdit());
            $browser->type('name', 'My LAN Venue');

            // And submits the form
            $browser->press('@submit');

            // Then they should be redirected to the venue's page
            $browser->assertRouteIs('venues.show', ['venue' => $venue->id]);

            // And they should see the venue's new name
            $browser->assertSee('My LAN Venue');
        });
    }
}
