<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Slides;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Slides\SlideIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class PublishSlideTest extends DuskTestCase
{
    public function test_publishing_and_unpublishing_slide(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is an unpublished slide
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Music',
                'content' => 'Give your music requests to the crew table',
                'position' => 1,
                'duration' => 1,
                'published' => false,
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the slide index page
            $browser->visitRoute('lans.slides.index', ['lan' => $lan]);
            $browser->on(new SlideIndex);

            // Note: the LAN header on this page also has its own Options dropdown,
            // so assertions below are scoped to the slide's own row.
            $rowXPath = '//a[text()="'.$slide->name.'"]//..//..';

            // And clicks the options dropdown in the same row as the slide's name
            $browser->clickAtXPath($rowXPath.'//button[@title="Options"]');

            // Then the dropdown shows only the "Publish" action
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

            // Then the slide is published
            $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => true]);

            // And clicking the options dropdown again
            $browser->clickAtXPath($rowXPath.'//button[@title="Options"]');

            // Shows only the "Unpublish" action
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

            // Then the slide is unpublished again
            $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => false]);
        });
    }
}
