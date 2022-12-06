<?php

namespace Tests\Browser\Tests\Achievements;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Achievements\AchievementCreate;
use Tests\Browser\Pages\Achievements\AchievementEdit;
use Tests\Browser\Pages\Achievements\AchievementIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Achievement;

class EditAchievementTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEditingAchievement()
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

            // When the super admin navigates to the achievement index page
            $browser->visitRoute('achievements.index');

            // And clicks the options dropdown in the same row as the achievement's name
            $browser->on(new AchievementIndex());
            $browser->clickAtXPath(
                '//a[contains(string(), "' . $achievement->name . '")]//..//..//button[@title="Options"]'
            );

            // And clicks the "edit" link
            $browser->clickLink('Edit');

            // And fills the "edit achievement" form
            $browser->waitForRoute('achievements.edit', ['achievement' => $achievement])
                ->on(new AchievementEdit())
                ->type('name', 'Eager Beaver')
                ->type('description', 'Be the first person to arrive at the LAN');

            // And submits the form
            $browser->press('@submit');

            // Then the super admin is redirected to the "show achievement" page
            $browser->assertRouteIs('achievements.show', ['achievement' => $achievement]);

            // And sees the updated achievement name
            $browser->assertSee('Eager Beaver');
        });
    }
}
