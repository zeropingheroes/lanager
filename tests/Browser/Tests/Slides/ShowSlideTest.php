<?php

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\Lan;

class ShowSlideTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testShowingSlides(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            // And the LAN is published
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
                'published' => true,
            ]);

            // And there is a slide
            // And the slide is marked as published
            $slide = Slide::create([
                'lan_id' => $lan->id,
                'name' => 'Music',
                'content' => 'Give your music requests to the crew table',
                'position' => 1,
                'duration' => 1,
                'published' => true,
            ]);

            // And there is a user with the super admin role
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin user visits the slide index page
            $browser->visitRoute('lans.slides.index', ['lan' => $lan]);

            // And they click the slide title in the table
            $browser->clickLink($slide->name);

            // Then they are taken to the show slide page
            $browser->assertRouteIs('lans.slides.show', ['lan' => $lan, 'slide' => $slide]);

            // And they see the slide's content
            $browser->waitFor('div.slide-container')->assertSee($slide->content);
        });
    }
}
