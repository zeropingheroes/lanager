<?php

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class DeleteLanTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingLan(): void
    {
        $this->browse(function (Browser $browser) {
            $superAdmin = $this->createSuperAdmin();
            $lan = Lan::factory()->count(1)->create()->first();

            $browser->loginAs($superAdmin)
                ->visit(new LanIndex())
                ->clickAtXPath('//a[text()="' . $lan->name . '"]//..//..//button[@title="Options"]')
                ->clickLink('Delete')
                ->assertDialogOpened('Are you sure you want to delete this?')
                ->acceptDialog()
                ->assertRouteIs('lans.index')
                ->assertSee('LAN "' . $lan->name . '" deleted');
        });
    }
}
