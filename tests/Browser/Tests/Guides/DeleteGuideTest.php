<?php

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Guides\GuideShow;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class DeleteGuideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testDeletingGuide()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is a guide
            $guide = Guide::create([
                'title' => 'Food and drink',
                'content' => 'Doritos and Mountain Dew',
                'lan_id' => $lan->id,
            ]);

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the show guide page
            $browser->visitRoute('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);

            // And clicks the options dropdown button
            $browser->on(new GuideShow())->clickAtXPath('//button[@title="Options"]');

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And confirms the deletion
            $browser->acceptDialog();

            // And waits to be redirected to the guide index page
            $browser->waitForRoute('lans.guides.index', ['lan' => $lan]);

            // Then they should see a deletion confirmation alert
            $browser->assertSee('Guide "' . $guide->title . '" deleted');
        });
    }
}
