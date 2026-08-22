<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventDiscordNotificationMessageCreate;
use Tests\Browser\Pages\Events\EventDiscordNotificationMessageEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class EventDiscordNotificationMessageTest extends DuskTestCase
{
    public function test_discord_section_shows_only_create_link_when_no_notification_message_exists(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with an event but no notification message
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the event show page
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // And opens the options dropdown
            $browser->click('button[title="Options"]');

            // Then the Discord section header is visible
            $browser->assertSee('Discord');

            // And only the Create link is shown
            $browser->assertSee('Create Notification Message');

            // And no record-dependent items are shown
            $browser->assertDontSee('Edit Notification Message');
            $browser->assertDontSee('Preview in Test Channel');
            $browser->assertDontSee('Send Now');
            $browser->assertDontSee('Delete Notification Message');
        });
    }

    public function test_send_button_is_disabled_when_no_live_webhook_configured(): void
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

    public function test_preview_button_is_disabled_on_create_page_when_no_test_webhook_configured(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with no test webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the notification create page
            $browser->visitRoute('lans.events.discord-notification-message.create', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventDiscordNotificationMessageCreate);

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

    public function test_preview_button_is_disabled_on_edit_page_when_no_test_webhook_configured(): void
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

    public function test_discord_section_not_shown_to_non_admin(): void
    {
        $this->browse(function (Browser $browser): void {
            // Create a super admin first — UserObserver assigns super-admin to the first user created
            // after DB truncation, so the non-admin must be created second
            $this->createSuperAdmin();

            // Given there is a non-admin user
            $user = User::factory()
                ->has(UserOAuthAccount::factory()->count(1), 'accounts')
                ->create();

            // And there is a published LAN with a published event and notification message
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00', 'published' => true]);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00', 'published' => true]);
            EventDiscordNotificationMessage::factory()->for($event)->create();

            // And the non-admin user is logged in (log out first to clear any previous super-admin session)
            $browser->logout();
            $browser->loginAs($user);

            // When the non-admin navigates to the event show page
            $browser->visitRoute('lans.events.show', ['lan' => $lan, 'event' => $event]);

            // Then the actions dropdown is not shown
            $browser->assertMissing('button[title="Options"]');

            // And the Discord section is not visible
            $browser->assertDontSee('Discord');
        });
    }

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

    public function test_preview_inline_result_shown_on_notification_create_page(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with a test webhook
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            DiscordChannelWebhook::factory()->test()->for($lan)->create();

            // And there is an event
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the notification create page
            $browser->visitRoute('lans.events.discord-notification-message.create', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventDiscordNotificationMessageCreate);

            // And types a notification message
            $browser->type('message', 'Test notification message');

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

            // And no page navigation occurs — still on the create page
            $browser->on(new EventDiscordNotificationMessageCreate);

            // And no notification message record is created
            $this->assertDatabaseMissing('event_discord_notification_messages', ['event_id' => $event->id]);
        });
    }

    public function test_preview_inline_result_shown_on_notification_edit_page(): void
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
