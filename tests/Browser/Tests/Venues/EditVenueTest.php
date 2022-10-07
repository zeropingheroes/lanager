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
            $superAdmin = $this->createSuperAdmin();
            $venue = Venue::factory()->count(1)->create()->first();

            $browser->loginAs($superAdmin)
                ->visit(new VenueIndex())
                ->clickAtXPath('//a[text()="' . $venue->name . '"]//..//..//button[@title="Options"]')
                ->clickLink('Edit')
                ->assertRouteIs('venues.edit', ['venue' => $venue->id])
                ->assertSee('Edit Venue')
                ->on(new VenueEdit())
                ->type('name', 'My LAN Venue')
                ->press('@submit')
                ->assertRouteIs('venues.show', ['venue' => $venue->id])
                ->assertSee('My LAN Venue');
        });
    }
}
