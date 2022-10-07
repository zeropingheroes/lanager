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
            $superAdmin = $this->createSuperAdmin();
            $venue = Venue::factory()->count(1)->create()->first();

            $browser->loginAs($superAdmin)
                ->visit(new VenueIndex())
                ->clickAtXPath('//a[text()="' . $venue->name . '"]//..//..//button[@title="Options"]')
                ->clickLink('Delete')
                ->assertDialogOpened('Are you sure you want to delete this?')
                ->acceptDialog()
                ->assertRouteIs('venues.index')
                ->assertSee('Venue "' . $venue->name . '" deleted');
        });
    }
}
