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
            $venue = Venue::factory()->count(1)->create()->first();

            $browser->visit(new VenueIndex())
                ->assertSee($venue->name);
        });
    }
}
