<?php

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Guides\GuideEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class EditGuideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEditingGuide()
    {
        $this->browse(function (Browser $browser) {

            // Given there is a LAN
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

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the show guide page
            $browser->visitRoute('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);

            // And clicks the options dropdown button
            $browser->clickAtXPath('//button[@title="Options"]');

            // And clicks the "delete" link
            $browser->clickLink('Edit');

            // And waits for the edit guide page to load
            $browser->waitForRoute('lans.guides.edit', ['lan' => $lan, 'guide' => $guide]);

            // And fills the "edit guide" form
            $browser->on(new GuideEdit());
            $browser->type('title', 'Code of conduct');
            $browser->type('content', 'Be excellent to each other');

            // And submits the form
            $browser->press('@submit');

            // Then the super admin is redirected to the "show guide" page
            $browser->assertRouteIs('lans.guides.show', ['lan' => $lan, 'guide' => '*']);

            // And sees the updated guide title
            $browser->assertSee('Code of conduct');

            // And sees the updated guide content
            $browser->assertSee('Be excellent to each other');
        });
    }
}
