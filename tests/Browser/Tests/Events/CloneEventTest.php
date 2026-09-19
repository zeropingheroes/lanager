<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Events;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class CloneEventTest extends DuskTestCase
{
    public function test_cloning_an_event_from_the_events_index_actions_dropdown(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with an event
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
                'published' => true,
            ]);
            $event = Event::factory()->create([
                'lan_id' => $lan->id,
                'name' => 'Overwatch',
                'description' => 'Team-based shooter',
                'start' => '2026-06-05 18:00:00',
                'end' => '2026-06-05 21:00:00',
                'published' => true,
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the LAN's events index page
            $browser->visitRoute('lans.events.index', ['lan' => $lan]);

            // And opens the event row's "options" dropdown (scoped to the events table, since
            // the LAN header above it has its own "Options" dropdown with a "Clone" link too)
            $browser->clickAtXPath('//table//button[@title="Options"]');

            // And clicks the event's "Clone" link
            $browser->clickAtXPath('//table//a[contains(normalize-space(.), "Clone")]');

            // And waits for the clone form to load
            $browser->waitForRoute('lans.events.clone.create', ['lan' => $lan->id, 'event' => $event->id]);

            // Then the form is pre-filled with the source event's values
            $browser->assertInputValue('name', 'Overwatch');
            $browser->assertSeeIn('textarea[name=description]', 'Team-based shooter');
            $browser->assertInputValue('start', '2026-06-05 18:00');
            $browser->assertInputValue('end', '2026-06-05 21:00');

            // When the super admin submits the form as-is
            $browser->waitForReload(function (Browser $browser): void {
                $browser->script('document.querySelector("button[type=submit]").click();');
            });

            // Then they are redirected to the new event's show page within the destination LAN
            $newEvent = Event::where('name', 'Overwatch')->where('id', '!=', $event->id)->firstOrFail();
            $browser->assertRouteIs('lans.events.show', ['lan' => $lan->id, 'event' => $newEvent->id]);
            $browser->assertSee('Overwatch');
        });
    }

    public function test_selecting_a_destination_lan_shifts_dates_and_shows_a_warning_when_out_of_range(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a source LAN with an event that starts 1 day after the LAN's own start
            $sourceLan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $event = Event::factory()->create([
                'lan_id' => $sourceLan->id,
                'name' => 'The Ship',
                'start' => '2026-06-06 18:00:00',
                'end' => '2026-06-06 21:00:00',
            ]);

            // And a short destination LAN the shifted event would fall outside of
            $shortLan = Lan::factory()->create([
                'start' => '2026-07-01 12:00:00',
                'end' => '2026-07-01 18:00:00',
            ]);

            // And a roomy destination LAN the shifted event comfortably fits within
            $roomyLan = Lan::factory()->create([
                'start' => '2026-08-01 12:00:00',
                'end' => '2026-08-05 12:00:00',
            ]);

            $user = $this->createSuperAdmin();
            $browser->loginAs($user);

            $browser->visitRoute('lans.events.clone.create', ['lan' => $sourceLan, 'event' => $event]);

            // No warning is shown while the destination LAN is still the event's own
            $browser->assertNotPresent('#clone-form-out-of-range-warning:not([hidden])');

            // When the short LAN is selected as the destination
            $browser->select('lan_id', (string) $shortLan->id);
            $browser->pause(250);

            // Then the start/end dates shift onto its schedule (1 day after its start), preserving time of day
            $browser->assertInputValue('start', '2026-07-02 18:00');
            $browser->assertInputValue('end', '2026-07-02 21:00');

            // And the out-of-range warning is shown, since that falls after the short LAN's end
            $browser->assertPresent('#clone-form-out-of-range-warning:not([hidden])');

            // When the roomy LAN is selected instead
            $browser->select('lan_id', (string) $roomyLan->id);
            $browser->pause(250);

            // Then the dates shift again, preserving the 1-day offset and time of day
            $browser->assertInputValue('start', '2026-08-02 18:00');
            $browser->assertInputValue('end', '2026-08-02 21:00');

            // And the warning is gone, since that falls comfortably within the roomy LAN
            $browser->assertNotPresent('#clone-form-out-of-range-warning:not([hidden])');
        });
    }

    public function test_manually_adjusting_start_or_signups_open_preserves_their_durations(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a LAN with an event that has a 3-hour duration and a 90-minute signup window
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $event = Event::factory()->create([
                'lan_id' => $lan->id,
                'name' => 'The Ship',
                'start' => '2026-06-05 18:00:00',
                'end' => '2026-06-05 21:00:00',
                'signups_open' => '2026-06-05 16:00:00',
                'signups_close' => '2026-06-05 17:30:00',
            ]);

            $user = $this->createSuperAdmin();
            $browser->loginAs($user);

            $browser->visitRoute('lans.events.clone.create', ['lan' => $lan, 'event' => $event]);

            // When the admin moves the start time 2 hours later
            $browser->type('start', '2026-06-05 20:00');
            $browser->click('h1');
            $browser->pause(250);

            // Then the end time shifts by the same 2 hours, preserving the 3-hour duration
            $browser->assertInputValue('end', '2026-06-05 23:00');

            // When the admin moves the signup-open time 1 hour earlier
            $browser->type('signups_open', '2026-06-05 15:00');
            $browser->click('h1');
            $browser->pause(250);

            // Then the signup-close time shifts by the same hour, preserving the 90-minute window
            $browser->assertInputValue('signups_close', '2026-06-05 16:30');
        });
    }
}
