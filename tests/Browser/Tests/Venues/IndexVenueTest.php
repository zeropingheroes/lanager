<?php

namespace Tests\Browser\Tests\Venues;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Venues\VenueIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Venue;

class IndexVenueTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingVenue(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a venue
            $venue = Venue::factory()->count(1)->create()->first();

            // When an unauthenticated user visits the venue index page
            $browser->visit(new VenueIndex());

            // Then they should see the venue's name
            $browser->assertSee($venue->name);
        });
    }
}
