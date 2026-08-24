<?php

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventEdit;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EditEventDoesNotShowDiscordNotificationMessageCheckboxTest extends DuskTestCase
{
    public function test_edit_page_has_no_discord_notification_message_checkbox(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN with an event
            $lan = Lan::create(['name' => 'My Great LAN', 'start' => '2025-06-01 18:00', 'end' => '2025-06-03 18:00']);
            $event = Event::create(['lan_id' => $lan->id, 'name' => 'My LAN Event', 'start' => '2025-06-01 19:00', 'end' => '2025-06-01 20:00']);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the "edit event" page
            $browser->visitRoute('lans.events.edit', ['lan' => $lan, 'event' => $event]);
            $browser->on(new EventEdit);

            // Then no "Create default Discord notification message" checkbox is shown
            $browser->assertDontSee('Create default Discord notification message');
            $browser->assertMissing('#create_default_discord_notification_message');
        });
    }
}
