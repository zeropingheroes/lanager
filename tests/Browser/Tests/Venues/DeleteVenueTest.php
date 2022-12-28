<?php

namespace Tests\Browser\Tests\Venues;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Venues\VenueIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Venue;

class DeleteVenueTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingVenue(): void
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

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // Then they should be redirected to the venue index page
            $browser->assertRouteIs('venues.index');

            // And they should see a confirmation message that the venue was deleted
            $browser->assertSee('Venue "' . $venue->name . '" deleted');
        });
    }
}
