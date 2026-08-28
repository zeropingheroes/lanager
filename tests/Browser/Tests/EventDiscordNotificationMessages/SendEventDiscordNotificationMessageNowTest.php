<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class SendEventDiscordNotificationMessageNowTest extends DuskTestCase
{
    public function test_send_now_shows_inline_result_without_page_navigation(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with a live webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            DiscordChannelWebhook::factory()->live()->for($lan)->create();

            // And there is an event with a notification message
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);
            EventDiscordNotificationMessage::factory()->for($event)->create();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the event show page
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // And opens the options dropdown
            $browser->click('button[title="Options"]');
            $browser->pause(300);

            // And clicks "Send Now" and accepts the confirmation dialog
            $browser->clickLink('Send Now');
            $browser->assertDialogOpened("Sending now will disable sending at the event's start time. Continue?");
            $browser->acceptDialog();

            // Then an inline result message is shown in the page alerts area (success or error)
            $browser->waitFor('#page-alerts .alert', 10);

            // And no page navigation occurs — still on the event show page
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event]);
        });
    }
}
