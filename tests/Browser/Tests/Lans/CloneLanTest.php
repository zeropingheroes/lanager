<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Lans;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanClone;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class CloneLanTest extends DuskTestCase
{
    public function test_cloning_a_lan_with_a_selected_subset(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a published LAN with two guides and two events
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
                'published' => true,
            ]);

            $keptGuide = Guide::create([
                'lan_id' => $lan->id,
                'title' => 'Wifi Guide',
                'content' => 'Connect to LAN wifi',
                'published' => true,
            ]);
            $droppedGuide = Guide::create([
                'lan_id' => $lan->id,
                'title' => 'Rules Guide',
                'content' => 'Be nice',
                'published' => true,
            ]);

            $eventOne = Event::factory()->create([
                'lan_id' => $lan->id,
                'name' => 'Overwatch',
                'start' => '2026-06-05 18:00:00',
                'end' => '2026-06-05 21:00:00',
            ]);
            $eventTwo = Event::factory()->create([
                'lan_id' => $lan->id,
                'name' => 'Valorant',
                'start' => '2026-06-06 10:00:00',
                'end' => '2026-06-06 13:00:00',
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the LAN's own page
            $browser->visit('/lans/'.$lan->id);
            $browser->waitForRoute('lans.events.index', ['lan' => $lan->id]);

            // And opens the "options" dropdown
            $browser->clickAtXPath('//button[@title="Options"]');

            // And the dropdown's "clone" item shows an icon
            $browser->assertPresent('.dropdown-menu i.fa-copy');

            // And clicks the "clone" link
            $browser->clickLink('Clone');

            // And waits for the "clone LAN" page to load
            $browser->waitForRoute('lans.clone.create', ['lan' => $lan->id]);
            $browser->on(new LanClone);

            // And fills in the new LAN's name and dates
            $browser->type('name', 'Cloned LAN');
            $browser->type('start', '2026-07-01 12:00');
            $browser->keys('#start', '{escape}');
            $browser->type('end', '2026-07-03 12:00');
            $browser->keys('#end', '{escape}');

            // And deselects one of the two guides, leaving the other selected
            $browser->uncheck('[data-selection-table="guides"] input[data-item-id="'.$droppedGuide->id.'"]');

            // And exercises the header checkbox to select none then all on the events table
            // (Clicked via JS rather than click() - the page is tall enough on this form that
            // the local-environment debug toolbar docked to the viewport bottom can otherwise
            // intercept a native click on this row.)
            $toggleAllEvents = 'document.querySelector(\'[data-selection-table="events"] [data-action="toggle-all"]\').click();';
            $browser->script($toggleAllEvents);
            $browser->pause(250);
            $browser->assertNotChecked('[data-selection-table="events"] input[data-item-id="'.$eventOne->id.'"]');
            $browser->script($toggleAllEvents);
            $browser->pause(250);
            $browser->assertChecked('[data-selection-table="events"] input[data-item-id="'.$eventOne->id.'"]');
            $browser->assertChecked('[data-selection-table="events"] input[data-item-id="'.$eventTwo->id.'"]');

            // Guide/Event links, computed client-side, should point at the source LAN's items
            $browser->assertAttribute('[data-selection-table="guides"] tr:has(input[data-item-id="'.$keptGuide->id.'"]) a[target=_blank]', 'href', '/lans/'.$lan->id.'/guides/'.$keptGuide->id);
            $browser->assertAttribute('[data-selection-table="events"] tr:has(input[data-item-id="'.$eventOne->id.'"]) a[target=_blank]', 'href', '/lans/'.$lan->id.'/events/'.$eventOne->id);

            // And submits the form
            // (Clicked via JS rather than press() - the page is tall enough on this form that
            // the local-environment debug toolbar docked to the viewport bottom can otherwise
            // intercept a native click on the submit button.)
            $browser->waitForReload(function (Browser $browser): void {
                $browser->script('document.querySelector("button[type=submit]").click();');
            });

            $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();

            // Then they should be redirected to the new LAN's event list page
            $browser->assertRouteIs('lans.events.index', ['lan' => $newLan->id]);

            // And they should see both selected events
            $browser->assertSee('Overwatch');
            $browser->assertSee('Valorant');

            // And the new LAN should be shown as a draft in its title
            $browser->assertSeeIn('h1', trans('title.draft'));

            // And the new LAN's guides should only include the selected one
            $browser->visit('/lans/'.$newLan->id.'/guides');
            $browser->assertSee('Wifi Guide');
            $browser->assertDontSee('Rules Guide');
        });
    }

    public function test_overflow_warning_shows_live_for_an_event_outside_the_new_lans_range(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with an event 30 hours into it
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $event = Event::factory()->create([
                'lan_id' => $lan->id,
                'name' => 'The Ship',
                'start' => '2026-06-06 18:00:00', // +30h from LAN start
                'end' => '2026-06-06 21:00:00',
            ]);

            $user = $this->createSuperAdmin();
            $browser->loginAs($user);

            $browser->visit('/lans/'.$lan->id.'/clone');
            $browser->on(new LanClone);

            $eventRowWarning = '[data-selection-table="events"] [data-out-of-range][data-item-id="'.$event->id.'"]';

            // When only a 6-hour new LAN is entered - the event's shifted time (+30h to +33h)
            // falls outside it
            $browser->type('name', 'Short LAN');
            $browser->type('start', '2026-07-01 12:00');
            // Blur Start (its picker fires its change event, which auto-fills End, on blur) and
            // let that settle before overwriting End with the test's own value.
            $browser->click('h1');
            $browser->pause(250);
            $browser->type('end', '2026-07-01 18:00');
            $browser->click('h1');
            $browser->pause(250);

            // Then a warning is shown next to the event, and the form-level summary help text
            $browser->assertPresent($eventRowWarning);
            $browser->assertPresent('[data-past-lan-end-summary]');

            // When the new LAN is widened to comfortably cover the event's shifted time
            $browser->type('end', '2026-07-03 12:00');
            $browser->click('h1');
            $browser->pause(250);

            // Then the warning and the summary help text are both gone
            $browser->assertNotPresent($eventRowWarning);
            $browser->assertNotPresent('[data-past-lan-end-summary]');
        });
    }
}
