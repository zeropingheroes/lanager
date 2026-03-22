<?php

namespace Tests\Browser\Tests\Images;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteImageTest extends DuskTestCase
{
    public function test_deleting_image(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the image index page
            $browser->visitRoute('images.index');

            // And selects a file to upload
            $browser->attach('images[]', base_path('resources/images/bg.jpg'));

            // And clicks the "upload" button
            $browser->waitForReload(function (Browser $browser) {
                $browser->press('Upload');
            });

            // And clicks the "options" dropdown next to the user's name
            $browser->clickAtXPath(
                '//a[contains(string(), "bg.jpg")]//..//..//button[@title="Options"]'
            );

            // And clicks the "delete" button
            $browser->clickLink('Delete');

            // And accept the deletion confirmation dialog
            $browser->acceptDialog();

            // Then the super admin should be redirected to the image index page
            $browser->assertRouteIs('images.index');

            // And should not see the image file name in the table
            $browser->assertDontSeeIn('table', 'bg.jpg');

            $browser->assertSee('Image "bg.jpg" deleted');
        });
    }
}
