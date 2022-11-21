<?php

namespace Tests\Browser\Tests\Achievements;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Achievements\AchievementCreate;
use Tests\DuskTestCase;

class CreateAchievementTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testIndexingAchievements(): void
    {
        $this->browse(function (Browser $browser) {
            // Given there is a super admin
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin user visits the achievement index page
            $browser->visitRoute('achievements.index');

            // And they click the "create achievement" button
            $browser->clickAtXPath('//a[@title="Create Achievement"]');

            // And fills the "create achievement" form
            $browser->waitForRoute('achievements.create')
                ->on(new AchievementCreate())
                ->type('name', 'I\'m Blue')
                ->type('description', 'Get a BSOD');

            // And submits the form
            $browser->press('@submit');

            // Then the super admin is redirected to the "show achievement" page
            $browser->assertRouteIs('achievements.show', ['achievement' => '*']);

            // And sees the achievement name
            $browser->assertSee('I\'m Blue');

            // And sees the achievement description
            $browser->assertSee('Get a BSOD');
        });
    }
}
