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
            $superAdmin = $this->createSuperAdmin();
            $lan = Lan::factory()->count(1)->create()->first();

            $browser->loginAs($superAdmin)
                ->visit(new LanIndex())
                ->clickAtXPath('//a[text()="' . $lan->name . '"]//..//..//button[@title="Options"]')
                ->clickLink('Edit')
                ->assertRouteIs('lans.edit', ['lan' => $lan->id])
                ->assertSee('Edit LAN')
                ->on(new LanEdit())
                ->type('name', 'My Great LAN')
                ->press('@submit')
                ->assertRouteIs('lans.events.index', ['lan' => $lan->id])
                ->assertSee('My Great LAN');
        });
    }
}
