<?php

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class IndexLanTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingLan(): void
    {
        $this->browse(function (Browser $browser) {
            $lan = Lan::factory()->count(1)->create()->first();

            $browser->visit(new LanIndex())
                ->assertSee($lan->name);
        });
    }
}
