<?php

namespace Tests\Browser\Tests\NavigationLinks;

use Laravel\Dusk\Browser;
use Zeropingheroes\Lanager\Models\NavigationLink;
use Tests\DuskTestCase;

class DeleteNavigationLinkTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingNavigationLink(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // And there is a navigation link
            $navigationLink = NavigationLink::create([
                'title' => 'Code of Conduct',
                'url' => '/lans/1/guides/1',
                'position' => '10',
            ]);

            // When the super admin visits the home page
            $browser->visitRoute('navigation-links.index');

            // And clicks the options dropdown in the same row as the navigation link's title
            $browser->clickAtXPath(
                '//td[contains(string(), "' . $navigationLink->title . '")]//..//button[@title="Options"]'
            );

            // And clicks the "edit" link
            $browser->clickLink('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // And waits for the page to load
            $browser->waitForRoute('navigation-links.index');

            // And they should see a confirmation message that the navigation link has been deleted
            $browser->assertSee('Navigation Link "' . $navigationLink->title . '" deleted');
        });
    }
}
