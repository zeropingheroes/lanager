<?php

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanEdit;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class EditLanTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testEditingLan(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::factory()->count(1)->create()->first();

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the LAN index page
            $browser->visit(new LanIndex());

            // And clicks the "options" dropdown next to the LAN's name
            $browser->clickAtXPath('//a[text()="' . $lan->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And waits for the "edit LAN" page to load
            $browser->waitForRoute('lans.edit', ['lan' => $lan->id]);

            // And updates the field for the LAN's name
            $browser->on(new LanEdit());
            $browser->type('name', 'My Great LAN');

            // And submits the form
            $browser->press('@submit');

            // Then they should be redirected to the LAN's event list page
            $browser->assertRouteIs('lans.events.index', ['lan' => $lan->id]);

            // And they should see the LAN's new name
            $browser->assertSee('My Great LAN');
        });
    }
}
