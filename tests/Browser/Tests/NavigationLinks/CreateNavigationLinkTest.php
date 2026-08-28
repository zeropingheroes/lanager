<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\NavigationLinks;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\NavigationLinks\NavigationLinkCreate;
use Tests\DuskTestCase;

class CreateNavigationLinkTest extends DuskTestCase
{
    public function test_creating_navigation_link(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

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
                ->on(new NavigationLinkCreate)
                ->type('title', 'Code of Conduct')
                ->type('url', '/lans/1/guides/1')
                ->type('position', '10');

            // And submits the form
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('@submit');
            });

            // Then they are redirected to the "navigation links index" page
            $browser->assertRouteIs('navigation-links.index');

            // And see the navigation link title
            $browser->assertSeeLink('Code of Conduct');
        });
    }
}
