<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\DiscordChannelWebhooks;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;

class SendTestDiscordChannelWebhookMessageTest extends DuskTestCase
{
    public function test_send_test_message_shows_inline_result_without_page_navigation(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with a Discord channel webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            DiscordChannelWebhook::factory()->test()->for($lan)->create();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the Discord channel webhooks page
            $browser->visitRoute('lans.discord-channel-webhooks.index', ['lan' => $lan]);

            // And clicks "Send Test Message" and accepts the confirmation dialog
            $browser->press('Send Test Message');
            $browser->acceptDialog();

            // Then an inline result message is shown in the page alerts area (success or error)
            $browser->waitFor('#page-alerts .alert', 10);

            // And no page navigation occurs — still on the webhooks index page
            $browser->assertRouteIs('lans.discord-channel-webhooks.index', ['lan' => $lan]);
        });
    }
}
