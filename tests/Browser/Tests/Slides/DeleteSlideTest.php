<?php

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Slides\SlideIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\Lan;

class DeleteSlideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testDeletingSlide()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And there is a LAN
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

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the slide index page
            $browser->visitRoute('lans.slides.index', ['lan' => $lan, 'slide' => $slide]);

            // And clicks the options dropdown in the same row as the slide's name
            $browser->on(new SlideIndex());
            $browser->clickAtXPath('//a[text()="' . $slide->name . '"]//..//..//button[@title="Options"]');

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And confirms the deletion
            $browser->acceptDialog();

            // Then the super admin should be taken to the slide index page
            $browser->assertRouteIs('lans.slides.index', ['lan' => $lan]);

            // And should see a deletion confirmation alert
            $browser->assertSee('Slide "' . $slide->name . '" deleted');
        });
    }
}
