<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Events\EventCreate;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class CreateEventWithDefaultDiscordNotificationMessageTest extends DuskTestCase
{
    public function test_checkbox_is_checked_by_default_and_creates_a_notification_message(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the "create event" page
            $browser->visitRoute('lans.events.create', ['lan' => $lan]);
            $browser->on(new EventCreate);

            // Then the "Create default Discord notification message" checkbox is shown, checked by default
            $browser->assertSee('Create default Discord notification message');
            $browser->assertChecked('create_default_discord_notification_message');

            // When they fill in and submit the form, leaving the checkbox checked
            $browser->type('name', 'My LAN Event');
            $browser->type('start', '2025-06-01 19:00');
            $browser->type('end', '2025-06-01 21:00');

            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('@submit');
            });

            // Then a Discord notification message has been created for the event, so the
            // options dropdown offers to edit it rather than create one
            $browser->click('button[title="Options"]');
            $browser->assertSee('Edit Notification Message');
            $browser->assertDontSee('Create Notification Message');
        });
    }
}
