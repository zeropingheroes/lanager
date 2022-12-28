<?php

namespace Tests\Browser\Tests\NavigationLinks;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\NavigationLink;

class IndexNavigationLinksTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingAchievements(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the navigation links index page
            $browser->visitRoute('navigation-links.index');

            // Then they should see all the navigation links in the database in the table
            foreach (NavigationLink::all() as $navigationLink) {
                $browser->assertSeeIn('.table', $navigationLink->title);
            }
        });
    }
}
