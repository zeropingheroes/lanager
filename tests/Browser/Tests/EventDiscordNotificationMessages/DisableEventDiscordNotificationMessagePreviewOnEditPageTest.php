<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\EventDiscordNotificationMessages\EventDiscordNotificationMessageEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class DisableEventDiscordNotificationMessagePreviewOnEditPageTest extends DuskTestCase
{
    public function test_disabling_preview_button_on_edit_page_when_no_test_webhook_configured(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with no test webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);
            EventDiscordNotificationMessage::factory()->for($event)->create();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the notification edit page
            $browser->visitRoute('lans.events.discord-notification-message.edit', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventDiscordNotificationMessageEdit);

            // Then the preview button is disabled
            $browser->assertPresent('#discord-notification-preview-button');
            $browser->assertScript('return document.getElementById("discord-notification-preview-button").disabled', true);

            // And the tooltip explains no test webhook is configured
            $browser->assertAttribute(
                '#discord-notification-preview-button',
                'title',
                'No test Discord webhook is configured for this LAN.'
            );
        });
    }
}
