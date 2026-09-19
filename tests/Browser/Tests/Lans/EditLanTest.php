<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanEdit;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class EditLanTest extends DuskTestCase
{
    public function test_editing_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            // (with fixed dates and a non-midnight time: a random midnight-hour time is shown as "24:15"
            // in the date picker in some browsers, which then fails the form's Y-m-d H:i validation)
            $lan = Lan::factory()->create([
                'start' => '2035-01-01 12:00:00',
                'end' => '2035-01-03 12:00:00',
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the LAN index page
            $browser->visit(new LanIndex);

            // And clicks the "options" dropdown next to the LAN's name
            $browser->clickAtXPath('//a[text()="'.$lan->name.'"]//..//..//button[@title="Options"]');

            // And the dropdown's "edit" item shows an icon
            $browser->assertPresent('.dropdown-menu i.fa-pen-to-square');

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And waits for the "edit LAN" page to load
            $browser->waitForRoute('lans.edit', ['lan' => $lan->id]);

            // And updates the field for the LAN's name
            $browser->on(new LanEdit);
            $browser->type('name', 'My Great LAN');

            // And submits the form
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('@submit');
            });

            // Then they should be redirected to the LAN's event list page
            $browser->assertRouteIs('lans.events.index', ['lan' => $lan->id]);

            // And they should see the LAN's new name
            $browser->assertSee('My Great LAN');
        });
    }

    public function test_editing_lan_with_midnight_hour_times(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN that starts and ends in the midnight hour
            $lan = Lan::factory()->create([
                'start' => '2035-01-01 00:15:00',
                'end' => '2035-01-03 00:15:00',
            ]);

            // And the super admin user is logged in
            $browser->loginAs($this->createSuperAdmin());

            // When they open the edit form, the times are shown as 00:15 (not 24:15)
            $browser->visitRoute('lans.edit', ['lan' => $lan->id]);
            $browser->on(new LanEdit);
            $browser->assertInputValue('start', '2035-01-01 00:15');
            $browser->assertInputValue('end', '2035-01-03 00:15');

            // And they can save the LAN
            $browser->type('name', 'Midnight LAN');
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('@submit');
            });

            // Then they are redirected to the LAN's event list page
            $browser->assertRouteIs('lans.events.index', ['lan' => $lan->id]);
            $browser->assertSee('Midnight LAN');
        });
    }
}
