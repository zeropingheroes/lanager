<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanCreate;
use Tests\Browser\Pages\Lans\LanEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class CreateLanWithDefaultDiscordNotificationMessageTest extends DuskTestCase
{
    public function test_create_page_shows_default_discord_notification_message_field(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the "create LAN" page
            $browser->visit(new LanCreate);

            // Then the "Default Discord notification message" field is shown with its placeholder and help text
            $browser->assertVisible('textarea[name="default_event_discord_notification_message"]');
            $browser->assertSee(trans('title.default-event-discord-notification-message'));
            $browser->assertSee(trans('phrase.default-event-discord-notification-message-help'));
        });
    }

    public function test_edit_page_prefills_existing_default_discord_notification_message(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with a default Discord notification message set
            $lan = Lan::factory()->create([
                'default_event_discord_notification_message' => 'Existing LAN default message',
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the "edit LAN" page
            $browser->visit(route('lans.edit', ['lan' => $lan]));
            $browser->on(new LanEdit);

            // Then the field is pre-filled with the LAN's existing default message
            $browser->assertInputValue('default_event_discord_notification_message', 'Existing LAN default message');
        });
    }
}
