<?php

namespace Tests\Browser\Tests\Achievements;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Achievement;

class DeleteAchievementTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testDeletingAchievement()
    {
        $this->browse(function (Browser $browser) {

            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And there is an achievement
            $achievement = Achievement::create([
                'name' => 'I\'m Blue',
                'description' => 'Get a BSOD',
            ]);

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin navigates to the achievement index
            $browser->visitRoute('achievements.index');

            // And clicks the options dropdown in the same row as the achievement's name
            $browser->clickAtXPath(
                '//a[contains(string(), "' . $achievement->name . '")]//..//..//button[@title="Options"]'
            );

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And confirms the deletion
            $browser->acceptDialog();

            // And waits to be redirected to the achievement index page
            $browser->waitForRoute('achievements.index');

            // Then they should see a confirmation message that the achievement was deleted
            $browser->assertSee('Achievement "' . $achievement->name . '" deleted');
        });
    }
}
