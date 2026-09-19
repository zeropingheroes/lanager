<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class SlideFormPickerLimitsTest extends DuskTestCase
{
    private const string WIDGET = '.tempus-dominus-widget.show';

    // The picker's "data-value" attributes use zero-indexed months, so find days by their "aria-label" instead
    private function assertDayEnabled(Browser $browser, string $label): void
    {
        $browser->assertPresent(self::WIDGET.' .day[aria-label="'.$label.'"]:not(.disabled)');
    }

    private function assertDayDisabled(Browser $browser, string $label): void
    {
        $browser->assertPresent(self::WIDGET.' .day[aria-label="'.$label.'"].disabled');
    }

    // The LAN starts in June, so the picker's "previous month" button must be disabled
    private function assertCannotGoBeforeLan(Browser $browser): void
    {
        $browser->assertPresent(self::WIDGET.' .previous.disabled');
    }

    public function test_create_form_pickers_reject_times_outside_the_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And a super admin is logged in
            $browser->loginAs($this->createSuperAdmin());

            // When the super admin opens the create slide form
            $browser->visitRoute('lans.slides.create', ['lan' => $lan]);

            // Then the start picker only allows days within the LAN
            $browser->click('#start')->waitFor(self::WIDGET);
            $this->assertDayEnabled($browser, 'June 01, 2025');
            $this->assertDayEnabled($browser, 'June 03, 2025');
            $this->assertCannotGoBeforeLan($browser);
            $this->assertDayDisabled($browser, 'June 04, 2025');

            // And the end picker only allows days within the LAN
            $browser->click('#position')->click('#end')->waitFor(self::WIDGET);
            $this->assertDayEnabled($browser, 'June 01, 2025');
            $this->assertDayEnabled($browser, 'June 03, 2025');
            $this->assertCannotGoBeforeLan($browser);
            $this->assertDayDisabled($browser, 'June 04, 2025');
        });
    }

    public function test_edit_form_pickers_reject_times_outside_the_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with a slide
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Code of conduct',
                'content' => 'Be excellent to each other',
                'position' => 1,
                'duration' => 10,
                'start' => '2025-06-01 20:00',
                'end' => '2025-06-02 20:00',
            ]);

            // And a super admin is logged in
            $browser->loginAs($this->createSuperAdmin());

            // When the super admin opens the edit slide form
            $browser->visitRoute('lans.slides.edit', ['lan' => $lan, 'slide' => $slide]);

            // Then the slide's current times are shown
            $browser->assertInputValue('#start', '2025-06-01 20:00')
                ->assertInputValue('#end', '2025-06-02 20:00');

            // And the end picker only allows days within the LAN and on or after the start
            $browser->click('#end')->waitFor(self::WIDGET);
            $this->assertDayEnabled($browser, 'June 03, 2025');
            $this->assertDayDisabled($browser, 'June 04, 2025');
            $this->assertCannotGoBeforeLan($browser);
        });
    }

    public function test_clone_form_pickers_are_not_limited_to_the_source_lan(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with a slide
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Code of conduct',
                'content' => 'Be excellent to each other',
                'position' => 1,
                'duration' => 10,
            ]);

            // And a super admin is logged in
            $browser->loginAs($this->createSuperAdmin());

            // When the super admin opens the clone slide form
            $browser->visitRoute('lans.slides.clone.create', ['lan' => $lan, 'slide' => $slide]);

            // Then the pickers have no LAN limits
            $browser->assertNotPresent('#lan-data');
        });
    }
}
