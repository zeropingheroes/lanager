<?php

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Guides\GuideCreate;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class CreateGuideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingGuide()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the guide index page
            $browser->visitRoute('lans.guides.index', ['lan' => $lan]);

            // And clicks the "create" link
            $browser->clickLink('Create');

            // And waits for the "create guide" page to load
            $browser->waitForRoute('lans.guides.create', ['lan' => $lan]);

            // And fills the "create guide" form
            $browser->on(new GuideCreate());
            $browser->type('title', 'Code of conduct');
            $browser->type('content', 'Be excellent to each other');

            // And submits the form
            $browser->press('@submit');

            // Then the super admin should be redirected to the "show guide" page
            $browser->assertRouteIs('lans.guides.show', ['lan' => $lan, 'guide' => '*']);

            // And should see the guide name
            $browser->assertSee('Code of conduct');

            // And should see the guide content
            $browser->assertSee('Be excellent to each other');
        });
    }
}
