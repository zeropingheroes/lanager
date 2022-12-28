<?php

namespace Tests\Browser\Tests\NavigationLinks;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\NavigationLinks\NavigationLinkCreate;
use Tests\DuskTestCase;

class CreateNavigationLinkTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testCreatingNavigationLink(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the home page
            $browser->visit('/');

            // And they click the admin menu
            $browser->click('#admin-menu');

            // And they click the "navigation" menu item
            $browser->clickLink('Navigation');

            // And they wait for the navigation links index page to load
            $browser->waitForRoute('navigation-links.index');

            // And they click the "create" button
            $browser->clickLink('Create');

            // And they fill the "create navigation link" form
            $browser->waitForRoute('navigation-links.create')
                ->on(new NavigationLinkCreate())
                ->type('title', 'Code of Conduct')
                ->type('url', '/lans/1/guides/1')
                ->type('position', '10');

            // And submits the form
            $browser->press('@submit');

            // Then they are redirected to the "navigation links index" page
            $browser->assertRouteIs('navigation-links.index');

            // And see the navigation link title
            $browser->assertSeeLink('Code of Conduct');
        });
    }
}
