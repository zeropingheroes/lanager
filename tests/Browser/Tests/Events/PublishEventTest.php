<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Events;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class PublishEventTest extends DuskTestCase
{
    public function test_publishing_and_unpublishing_event(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is an unpublished event
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
                'published' => false,
            ]);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Note: the LAN header on this page also has its own Options dropdown,
            // so assertions below are scoped to the event's own row.
            $rowXPath = '//a[text()="'.$event->name.'"]//..//..';

            // And clicks the "options" dropdown next to the event's name
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

            // Then the event is published
            $this->assertDatabaseHas('events', ['id' => $event->id, 'published' => true]);

            // And clicking the "options" dropdown again
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

            // Then the event is unpublished again
            $this->assertDatabaseHas('events', ['id' => $event->id, 'published' => false]);
        });
    }
}
