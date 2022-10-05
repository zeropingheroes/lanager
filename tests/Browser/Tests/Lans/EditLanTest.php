<?php

namespace Tests\Browser\Tests\Lans;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanEdit;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class EditLanTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function testEditingLan(): void
    {
        $this->browse(function (Browser $browser) {
            $superAdmin = $this->createSuperAdmin();
            $lan = Lan::factory()->count(1)->create()->first();
            $lanRowXPath = '//a[text()[contains(.,"' . $lan->name . '")]]//parent::td//parent::tr';
            $dropdownXPath = '//button[contains(@class, "dropdown-toggle")]';

            $browser->loginAs($superAdmin)
                ->visit(new LanIndex())
                ->clickAtXPath($lanRowXPath . $dropdownXPath)
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
