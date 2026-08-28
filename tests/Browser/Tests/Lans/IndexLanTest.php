<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class IndexLanTest extends DuskTestCase
{
    public function test_indexing_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            // And the LAN is published
            $lan = Lan::factory()->state(['published' => true])->count(1)->create()->first();

            // When an unauthenticated user visits the LAN index page
            $browser->visit(new LanIndex);

            // Then they should see the LAN's name
            $browser->assertSee($lan->name);
        });
    }
}
