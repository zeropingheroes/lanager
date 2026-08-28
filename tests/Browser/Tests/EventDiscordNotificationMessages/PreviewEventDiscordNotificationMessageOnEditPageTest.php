<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\EventDiscordNotificationMessages\EventDiscordNotificationMessageEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class PreviewEventDiscordNotificationMessageOnEditPageTest extends DuskTestCase
{
    public function test_previewing_notification_on_edit_page(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with a test webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            DiscordChannelWebhook::factory()->test()->for($lan)->create();

            // And there is an event with an existing notification message
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);
            EventDiscordNotificationMessage::factory()->for($event)->create(['message' => 'Original message']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the notification edit page
            $browser->visitRoute('lans.events.discord-notification-message.edit', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventDiscordNotificationMessageEdit);

            // And modifies the notification message
            $browser->clear('message');
            $browser->type('message', 'Modified message for preview');

            // Wait for the Vue image selector to finish mounting
            $browser->waitFor('.selection-panel');

            // Scroll the preview button to the centre of the viewport — the image library can push it
            // near the bottom where fixed page elements may intercept the click
            $browser->script("document.getElementById('discord-notification-preview-button').scrollIntoView({behavior: 'instant', block: 'center'})");
            $browser->pause(200);

            // And clicks the preview button
            $browser->click('#discord-notification-preview-button');

            // Then an inline result message is shown next to the preview button (success or error)
            $browser->waitFor('#discord-notification-preview-result.text-success, #discord-notification-preview-result.text-danger');

            // And no page navigation occurs — still on the edit page
            $browser->on(new EventDiscordNotificationMessageEdit);

            // And the stored notification message is unchanged
            $this->assertDatabaseHas('event_discord_notification_messages', [
                'event_id' => $event->id,
                'message' => 'Original message',
            ]);
        });
    }
}
