<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Lans;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class PublishLanTest extends DuskTestCase
{
    public function test_publishing_and_unpublishing_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is an unpublished LAN
            $lan = Lan::factory()->state(['published' => false])->count(1)->create()->first();

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the LAN index page
            $browser->visit(new LanIndex);

            $rowXPath = '//a[text()="'.$lan->name.'"]//..//..';

            // And clicks the "options" dropdown next to the LAN's name
            $browser->clickAtXPath($rowXPath.'//button[@title="Options"]');

            // Then the dropdown shows only the "Publish" action, scoped to this LAN's row
            $this->assertCount(1, $browser->driver->findElements(
                WebDriverBy::xpath($rowXPath.'//button[normalize-space(text())="Publish"]')
            ));
            $this->assertCount(0, $browser->driver->findElements(
                WebDriverBy::xpath($rowXPath.'//button[normalize-space(text())="Unpublish"]')
            ));

            // When they click "Publish"
            $browser->waitForReload(function (Browser $browser) use ($rowXPath): void {
                $browser->clickAtXPath($rowXPath.'//button[normalize-space(text())="Publish"]');
            });

            // Then the LAN is published
            $this->assertDatabaseHas('lans', ['id' => $lan->id, 'published' => true]);

            // And clicking the "options" dropdown again
            $browser->clickAtXPath($rowXPath.'//button[@title="Options"]');

            // Shows only the "Unpublish" action, scoped to this LAN's row
            $this->assertCount(1, $browser->driver->findElements(
                WebDriverBy::xpath($rowXPath.'//button[normalize-space(text())="Unpublish"]')
            ));
            $this->assertCount(0, $browser->driver->findElements(
                WebDriverBy::xpath($rowXPath.'//button[normalize-space(text())="Publish"]')
            ));

            // When they click "Unpublish"
            $browser->waitForReload(function (Browser $browser) use ($rowXPath): void {
                $browser->clickAtXPath($rowXPath.'//button[normalize-space(text())="Unpublish"]');
            });

            // Then the LAN is unpublished again
            $this->assertDatabaseHas('lans', ['id' => $lan->id, 'published' => false]);
        });
    }
}
