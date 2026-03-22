<?php

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class DeleteLanTest extends DuskTestCase
{
    public function test_deleting_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::factory()->count(1)->create()->first();

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the LAN index page
            $browser->visit(new LanIndex);

            // And clicks the "options" dropdown next to the LAN's name
            $browser->clickAtXPath('//a[text()="'.$lan->name.'"]//..//..//button[@title="Options"]');

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // Then they should be redirected to the LAN index page
            $browser->assertRouteIs('lans.index');

            // And they should see a confirmation message that the LAN has been deleted
            $browser->assertSee('LAN "'.$lan->name.'" deleted');
        });
    }
}
