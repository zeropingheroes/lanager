<?php

namespace Tests\Browser\Tests\Venues;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Venues\VenueIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Venue;

class IndexVenueTest extends DuskTestCase
{
    public function test_indexing_venue(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a venue
            $venue = Venue::factory()->count(1)->create()->first();

            // When an unauthenticated user visits the venue index page
            $browser->visit(new VenueIndex);

            // Then they should see the venue's name
            $browser->assertSee($venue->name);
        });
    }
}
