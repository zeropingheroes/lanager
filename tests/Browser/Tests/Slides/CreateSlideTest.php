<?php

namespace Tests\Browser\Tests\Slides;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Slides\SlideCreate;
use Tests\Browser\Pages\Slides\SlideIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;

class CreateSlideTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingSlide()
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

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the slide index page
            $browser->visitRoute('lans.slides.index', ['lan' => $lan]);

            // And clicks the "create" link
            $browser->on(new SlideIndex())->clickAtXPath('//a[@title="Create Slide"]');

            // And fills the "create slide" form
            $browser->waitForRoute('lans.slides.create', ['lan' => $lan])
                ->on(new SlideCreate())
                ->type('name', 'Code of conduct')
                ->type('content', 'Be excellent to each other')
                ->type('position', 1)
                ->type('duration', 10);

            // And submits the form
            $browser->press('@submit');

            // Then the super admin is redirected to the "show slide" page
            $browser->assertRouteIs('lans.slides.index', ['lan' => $lan]);

            // And sees the slide name
            $browser->assertSee('Code of conduct');
        });
    }
}
