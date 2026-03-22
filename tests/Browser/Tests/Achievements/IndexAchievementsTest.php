<?php

namespace Tests\Browser\Tests\Achievements;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Achievement;

class IndexAchievementsTest extends DuskTestCase
{
    public function test_indexing_achievements(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is an achievement
            $achievement = Achievement::create([
                'name' => 'Left the house',
                'description' => 'The graphics are great but the story is terrible',
            ]);

            // And there is a user with the super admin role
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // When the super admin user visits the achievement index page
            $browser->visitRoute('achievements.index');

            // Then they should see the achievement's name
            $browser->assertSee($achievement->name);
        });
    }
}
