<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class IndexEventTest extends DuskTestCase
{
    public function test_indexing_events(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            // And it is published
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And the LAN has an event
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            // When an unauthenticated user visits the LAN's event list page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Then they should see the event's name
            $browser->assertSee($event->name);
        });
    }

    public function test_out_of_time_range_warning_is_shown_to_an_admin_for_an_event_starting_before_the_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And the LAN has an event that starts before the LAN starts
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My Early Event',
                'start' => '2025-06-01 17:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Then they should see the "Out of time range" warning
            $browser->assertSee($event->name)
                ->assertSee('Out of time range');
        });
    }

    public function test_out_of_time_range_warning_is_not_shown_to_an_admin_for_an_event_within_the_lan_time_range(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And the LAN has an event fully within the LAN's time range
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My LAN Event',
                'start' => '2025-06-01 19:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Then they should not see the "Out of time range" warning
            $browser->assertSee($event->name)
                ->assertDontSee('Out of time range');
        });
    }

    public function test_out_of_time_range_warning_is_not_shown_to_an_unauthenticated_user_for_an_event_starting_before_the_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And the LAN has an event that starts before the LAN starts
            $event = Event::create([
                'lan_id' => $lan->id,
                'name' => 'My Early Event',
                'start' => '2025-06-01 17:00',
                'end' => '2025-06-01 20:00',
                'published' => true,
            ]);

            // When an unauthenticated user visits the LAN's event list page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // Then they should not see the "Out of time range" warning
            $browser->assertSee($event->name)
                ->assertDontSee('Out of time range');
        });
    }
}
