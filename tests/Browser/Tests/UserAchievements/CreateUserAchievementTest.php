<?php

namespace Tests\Browser\Tests\UserAchievements;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateUserAchievementTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingUserAchievement()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a LAN
            $lan = Lan::factory()->count(1)->create()->first();

            // And there is an achievement
            $achievement = Achievement::create([
                'name' => 'I\'m Blue',
                'description' => 'Get a BSOD',
            ]);

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And the user is attending the LAN
            $user->lans()->attach($lan->id);

            // And a user with the super admin role exists
            $superAdmin = $this->createSuperAdmin();

            // And the super admin is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the achievements page
            $browser->visitRoute('lans.user-achievements.index', ['lan' => $lan]);

            // And selects the user in the user dropdown
            $browser->select('user_id', $user->username);

            // And selects the achievement in the achievement dropdown
            $browser->select('achievement_id', $achievement->name);

            // And clicks the award achievement button
            $browser->press('Award');

            // And waits for the event's page to load
            $browser->waitForRoute('lans.user-achievements.index', ['lan' => $lan]);

            // Then the user's name should show inside the table of awarded achievements
            $browser->assertSeeIn('.table', $user->username);

            // And the achievement name should show inside the table of awarded achievements
            $browser->assertSeeIn('.table', $achievement->name);
        });
    }
}
