<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class HideEventDiscordNotificationMessageSectionFromNonAdminTest extends DuskTestCase
{
    public function test_hiding_discord_section_from_non_admin(): void
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
}
