<?php

namespace Tests\Browser\Tests\Images;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditImageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEditingImage()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // And there is an image in the image uploads directory
            copy(base_path('public/img/bg.jpg'), storage_path('app/public/images/bg.jpg'));

            // When the super admin navigates to the image index page
            $browser->visitRoute('images.index');

            // And clicks the "options" dropdown next to the user's name
            $browser->clickAtXPath(
                '//a[contains(string(), "bg.jpg")]//..//..//button[@title="Options"]'
            );

            // And clicks the "edit" button
            $browser->clickLink('Edit');

            // And waits for the "edit image" page to load
            $browser->waitForRoute('images.edit', ['image' => 'bg.jpg']);

            // And types in a new filename for the image
            $browser->type('filename', 'background.jpg');

            // And clicks "submit"
            $browser->press('Submit');

            // Then they should be redirected to the image index page
            $browser->assertRouteIs('images.index');

            // And should not see the old image file name in the table
            $browser->assertDontSeeIn('table', 'bg.jpg');

            // And they should see the new image file name in the table
            $browser->assertSeeIn('table', 'background.jpg');
        });
    }
}
