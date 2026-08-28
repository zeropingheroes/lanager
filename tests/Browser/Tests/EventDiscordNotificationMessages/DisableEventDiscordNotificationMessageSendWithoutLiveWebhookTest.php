<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class DisableEventDiscordNotificationMessageSendWithoutLiveWebhookTest extends DuskTestCase
{
    public function test_disabling_send_button_when_no_live_webhook_configured(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with a test webhook but no live webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            DiscordChannelWebhook::factory()->test()->for($lan)->create();

            // And there is an event with a notification message
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);
            EventDiscordNotificationMessage::factory()->for($event)->create();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the event show page
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // And opens the options dropdown
            $browser->click('button[title="Options"]');

            // Then the Send Now item is disabled
            $browser->assertPresent('.dropdown-item.disabled');

            // And the tooltip explains no live webhook is configured
            $browser->assertAttribute(
                'a.dropdown-item.disabled[title]',
                'title',
                'No live Discord webhook is configured for this LAN.'
            );
        });
    }
}
