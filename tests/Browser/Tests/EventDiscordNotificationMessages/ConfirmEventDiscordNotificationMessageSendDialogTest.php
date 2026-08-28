<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class ConfirmEventDiscordNotificationMessageSendDialogTest extends DuskTestCase
{
    public function test_send_confirmation_dialog_mentions_disabling_automatic_sending(): void
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

            // Wait for Bootstrap dropdown animation to complete
            $browser->pause(300);

            // And clicks the "Send Now" item in the Discord section
            $browser->clickLink('Send Now');

            // Then a confirmation dialog is shown mentioning that automatic sending will be disabled
            $browser->assertDialogOpened('Sending now will disable sending at the event\'s start time. Continue?');

            // And dismissing the dialog does not submit the form
            $browser->dismissDialog();

            // And the user remains on the event show page
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan, 'event' => $event]);
        });
    }
}
