<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\EventDiscordNotificationMessages\EventDiscordNotificationMessageEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class EditEventDiscordNotificationMessageTest extends DuskTestCase
{
    public function test_editing_event_discord_notification_message(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with an event that has an existing notification message
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);
            EventDiscordNotificationMessage::factory()->for($event)->create(['message' => 'Original message']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the notification edit page
            $browser->visitRoute('lans.events.discord-notification-message.edit', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventDiscordNotificationMessageEdit);

            // Wait for the Vue image selector to finish mounting
            $browser->waitFor('.selection-panel');

            // And updates the notification message
            $browser->clear('message');
            $browser->type('message', 'Updated message for My LAN Event');

            // Scroll the submit button to the centre of the viewport — the debug toolbar can
            // intercept the click when the button is near the bottom of the page
            $browser->script("document.querySelector('button[type=submit]').scrollIntoView({behavior: 'instant', block: 'center'})");
            $browser->pause(200);

            // And submits the form
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('@submit');
            });

            // Then they should be redirected to the event show page
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // And the notification message is updated
            $this->assertDatabaseHas('event_discord_notification_messages', [
                'event_id' => $event->id,
                'message' => 'Updated message for My LAN Event',
            ]);
        });
    }
}
