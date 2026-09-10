<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Guides\GuideShow;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class PublishGuideTest extends DuskTestCase
{
    public function test_publishing_and_unpublishing_guide(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is an unpublished guide
            $guide = Guide::create([
                'title' => 'Food and drink',
                'content' => 'Doritos and Mountain Dew',
                'lan_id' => $lan->id,
                'published' => false,
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the show guide page
            $browser->visitRoute('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);

            // And clicks the options dropdown button
            $browser->on(new GuideShow)->clickAtXPath('//button[@title="Options"]');

            // Then the dropdown shows only the "Publish" action
            $browser->assertPresent('.dropdown-menu i.fa-globe');
            $browser->assertNotPresent('.dropdown-menu i.fa-file-pen');

            // When they click "Publish"
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('Publish');
            });

            // Then the guide is published
            $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => true]);

            // And clicking the options dropdown again
            $browser->clickAtXPath('//button[@title="Options"]');

            // Shows only the "Unpublish" action
            $browser->assertPresent('.dropdown-menu i.fa-file-pen');
            $browser->assertNotPresent('.dropdown-menu i.fa-globe');

            // When they click "Unpublish"
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('Unpublish');
            });

            // Then the guide is unpublished again
            $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => false]);
        });
    }
}
