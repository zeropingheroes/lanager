<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Images;

use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditImageTest extends DuskTestCase
{
    public function test_editing_image(): void
    {
        // Clean up filesystem artifacts from any previous test runs so the rename validation
        // does not fail because the target filename already exists
        Storage::disk('public')->delete('images/newfilename.jpg');
        Storage::disk('public')->delete('images/bg.jpg');

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

            // And clicks the "options" dropdown next to the image's name
            $browser->clickAtXPath(
                '//a[contains(string(), "bg.jpg")]//..//..//button[@title="Options"]'
            );

            // And clicks the "edit" button
            $browser->clickLink('Edit');

            // And waits for the "edit image" page to load
            $browser->waitForRoute('images.edit', ['image' => 'bg.jpg']);

            // And types in a new filename for the image
            $browser->type('filename', 'newfilename.jpg');

            // And clicks "submit"
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('Submit');
            });

            // Then they should be redirected to the image index page
            $browser->assertRouteIs('images.index');

            // And should not see the old image file name in the table
            $browser->assertDontSeeIn('table', 'bg.jpg');

            // And they should see the new image file name in the table
            $browser->assertSeeIn('table', 'newfilename.jpg');
        });
    }
}
