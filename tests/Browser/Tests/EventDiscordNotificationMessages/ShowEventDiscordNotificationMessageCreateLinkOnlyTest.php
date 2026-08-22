<?php

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class ShowEventDiscordNotificationMessageCreateLinkOnlyTest extends DuskTestCase
{
    public function test_showing_only_create_link_when_no_notification_message_exists(): void
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
}
