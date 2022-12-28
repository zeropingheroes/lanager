<?php

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Slides\SlideEdit;
use Tests\Browser\Pages\Slides\SlideIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class EditSlideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEditingSlide()
    {
        $this->browse(function (Browser $browser) {

            // Given there is a LAN
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);

            // And there is a slide
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Music',
                'content' => 'Give your music requests to the crew table',
                'position' => 1,
                'duration' => 1,
            ]);

            // And there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the slide index page
            $browser->visitRoute('lans.slides.index', ['lan' => $lan]);

            // And clicks the options dropdown in the same row as the slide's name
            $browser->on(new SlideIndex());
            $browser->clickAtXPath('//a[text()="' . $slide->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And fills the "edit slide" form
            $browser->waitForRoute('lans.slides.edit', ['lan' => $lan, 'slide' => $slide])
                ->on(new SlideEdit())
                ->type('name', 'Code of conduct')
                ->type('content', 'Be excellent to each other')
                ->type('position', 1)
                ->type('duration', 10);

            // And submits the form
            $browser->press('@submit');

            // Then the super admin should be redirected to the "show slide" page
            $browser->assertRouteIs('lans.slides.index', ['lan' => $lan]);

            // And should see the updated slide name
            $browser->assertSee('Code of conduct');
        });
    }
}
