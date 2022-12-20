<?php

namespace Tests\Browser\Tests\NavigationLinks;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\NavigationLinks\NavigationLinkEdit;
use Zeropingheroes\Lanager\Models\NavigationLink;
use Tests\DuskTestCase;

class EditNavigationLinkTest extends DuskTestCase
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
            $browser->clickLink('Edit');

            // And on the edit form they change the navigation link's title
            $browser->waitForRoute('navigation-links.edit', ['navigation_link' => $navigationLink->id])
                ->on(new NavigationLinkEdit())
                ->type('title', 'Rules')
                ->type('url', '/lans/1/guides/123')
                ->type('position', '65');

            // And they submit the form
            $browser->press('@submit');

            // Then the super admin is redirected to the "navigation links index" page
            $browser->assertRouteIs('navigation-links.index');

            // And sees the new navigation link title, URL and position
            $browser->assertSee('Rules');
            $browser->assertSee('/lans/1/guides/123');
            $browser->assertSee('65');

            // And doesn't see the old title
            $browser->assertDontSee('Code of Conduct');
        });
    }
}
