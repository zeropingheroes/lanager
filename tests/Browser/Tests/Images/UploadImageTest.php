<?php

namespace Tests\Browser\Tests\Images;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UploadImageTest extends DuskTestCase
{
    public function test_uploading_image(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the image index page
            $browser->visitRoute('images.index');

            // And selects a file to upload
            $browser->attach('images[]', base_path('resources/images/bg.jpg'));

            // And clicks the "upload" button
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('Upload');
            });

            // Then the super admin should be redirected to the image index page
            $browser->assertRouteIs('images.index');

            // And should see the image file name in the table
            $browser->assertSeeIn('table', 'bg.jpg');
        });
    }
}
