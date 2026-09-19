<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Slides\SlideIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class CloneSlideTest extends DuskTestCase
{
    public function test_cloning_a_slide_from_the_slide_list_actions_dropdown(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with a slide
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Welcome',
                'content' => 'Welcome to the LAN',
                'position' => 2,
                'duration' => 15,
                'start' => '2026-06-05 18:00:00',
                'end' => '2026-06-05 21:00:00',
                'published' => true,
            ]);

            // And the super admin is logged in
            $browser->loginAs($this->createSuperAdmin());

            // When the super admin opens the slide list and the options dropdown in the slide's row
            $browser->visitRoute('lans.slides.index', ['lan' => $lan]);
            $browser->on(new SlideIndex);
            $browser->clickAtXPath('//a[text()="'.$slide->name.'"]//..//..//button[@title="Options"]');

            // And clicks the "Clone" link
            $browser->clickLink('Clone');
            $browser->waitForRoute('lans.slides.clone.create', ['lan' => $lan->id, 'slide' => $slide->id]);

            // Then the form is pre-filled with the source slide's values
            $browser->assertInputValue('name', 'Welcome');
            $browser->assertSeeIn('textarea[name=content]', 'Welcome to the LAN');
            $browser->assertInputValue('position', '2');
            $browser->assertInputValue('duration', '15');
            $browser->assertInputValue('start', '2026-06-05 18:00');
            $browser->assertInputValue('end', '2026-06-05 21:00');
            $browser->assertSelected('lan_id', (string) $lan->id);

            // When the super admin submits the form as-is
            $browser->waitForReload(function (Browser $browser): void {
                $browser->script('document.querySelector("button[type=submit]").click();');
            });

            // Then they are redirected to the slide list and a second slide with the same name exists
            $browser->assertRouteIs('lans.slides.index', ['lan' => $lan->id]);
            $this->assertSame(2, Slide::where('name', 'Welcome')->count());
        });
    }

    public function test_selecting_a_destination_lan_shifts_dates_and_shows_a_warning_when_out_of_range(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a source LAN with a slide that starts 1 day after the LAN's own start
            $sourceLan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $sourceLan->id,
                'name' => 'Schedule',
                'content' => 'Tonight',
                'position' => 1,
                'duration' => 10,
                'start' => '2026-06-06 18:00:00',
                'end' => '2026-06-06 21:00:00',
                'published' => true,
            ]);

            // And a short destination LAN the shifted slide would fall outside of
            $shortLan = Lan::factory()->create([
                'start' => '2026-07-01 12:00:00',
                'end' => '2026-07-01 18:00:00',
            ]);

            // And a roomy destination LAN the shifted slide comfortably fits within
            $roomyLan = Lan::factory()->create([
                'start' => '2026-08-01 12:00:00',
                'end' => '2026-08-05 12:00:00',
            ]);

            $browser->loginAs($this->createSuperAdmin());
            $browser->visitRoute('lans.slides.clone.create', ['lan' => $sourceLan, 'slide' => $slide]);

            // No warning is shown while the destination LAN is still the slide's own
            $browser->assertNotPresent('#clone-form-out-of-range-warning:not([hidden])');

            // When the short LAN is selected as the destination
            $browser->select('lan_id', (string) $shortLan->id);
            $browser->pause(250);

            // Then the dates shift onto its schedule, preserving time of day
            $browser->assertInputValue('start', '2026-07-02 18:00');
            $browser->assertInputValue('end', '2026-07-02 21:00');

            // And the out-of-range warning is shown
            $browser->assertPresent('#clone-form-out-of-range-warning:not([hidden])');

            // When the roomy LAN is selected instead
            $browser->select('lan_id', (string) $roomyLan->id);
            $browser->pause(250);

            // Then the dates shift again, preserving the 1-day offset and time of day
            $browser->assertInputValue('start', '2026-08-02 18:00');
            $browser->assertInputValue('end', '2026-08-02 21:00');

            // And the warning is gone
            $browser->assertNotPresent('#clone-form-out-of-range-warning:not([hidden])');
        });
    }

    public function test_moving_the_start_moves_the_end_by_the_same_amount(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a slide with a 3-hour active period
            $lan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Schedule',
                'content' => 'Tonight',
                'position' => 1,
                'duration' => 10,
                'start' => '2026-06-05 18:00:00',
                'end' => '2026-06-05 21:00:00',
                'published' => true,
            ]);

            $browser->loginAs($this->createSuperAdmin());
            $browser->visitRoute('lans.slides.clone.create', ['lan' => $lan, 'slide' => $slide]);

            // When the admin moves the start 2 hours later
            $browser->type('start', '2026-06-05 20:00');
            $browser->click('h1');
            $browser->pause(250);

            // Then the end moves by the same 2 hours
            $browser->assertInputValue('end', '2026-06-05 23:00');
        });
    }

    public function test_a_slide_with_no_start_or_end_keeps_them_empty_when_the_lan_changes(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a slide with no start or end
            $sourceLan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $otherLan = Lan::factory()->create([
                'start' => '2026-08-01 12:00:00',
                'end' => '2026-08-05 12:00:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $sourceLan->id,
                'name' => 'Always on',
                'content' => 'Hello',
                'position' => 1,
                'duration' => 10,
                'published' => true,
            ]);

            $browser->loginAs($this->createSuperAdmin());
            $browser->visitRoute('lans.slides.clone.create', ['lan' => $sourceLan, 'slide' => $slide]);

            // When another LAN is selected
            $browser->select('lan_id', (string) $otherLan->id);
            $browser->pause(250);

            // Then start and end stay empty and no warning is shown
            $browser->assertInputValue('start', '');
            $browser->assertInputValue('end', '');
            $browser->assertNotPresent('#clone-form-out-of-range-warning:not([hidden])');
        });
    }

    public function test_a_slide_with_only_an_end_shifts_the_end_when_the_lan_changes(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given a slide with an end but no start, 1 day after its LAN's start
            $sourceLan = Lan::factory()->create([
                'start' => '2026-06-05 12:00:00',
                'end' => '2026-06-07 12:00:00',
            ]);
            $otherLan = Lan::factory()->create([
                'start' => '2026-08-01 12:00:00',
                'end' => '2026-08-05 12:00:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $sourceLan->id,
                'name' => 'Until late',
                'content' => 'Hello',
                'position' => 1,
                'duration' => 10,
                'end' => '2026-06-06 21:00:00',
                'published' => true,
            ]);

            $browser->loginAs($this->createSuperAdmin());
            $browser->visitRoute('lans.slides.clone.create', ['lan' => $sourceLan, 'slide' => $slide]);

            // When another LAN is selected
            $browser->select('lan_id', (string) $otherLan->id);
            $browser->pause(250);

            // Then the end shifts onto the new LAN and the start stays empty
            $browser->assertInputValue('end', '2026-08-02 21:00');
            $browser->assertInputValue('start', '');
        });
    }
}
