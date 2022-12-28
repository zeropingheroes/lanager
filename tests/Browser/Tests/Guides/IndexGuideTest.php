<?php

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class IndexGuideTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingGuides(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            // And the LAN is published
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And there is a guide
            // And the guide is marked as published
            $guide = Guide::create([
                'lan_id' => $lan->id,
                'title' => 'Shop',
                'content' => 'We have things for sale',
                'published' => true,
            ]);

            // When an unauthenticated user visits the guide index page
            $browser->visitRoute('lans.guides.index', ['lan' => $lan]);

            // Then they should see the guide's title
            $browser->assertSee($guide->title);
        });
    }
}
