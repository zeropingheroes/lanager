<?php

namespace Tests\Browser\Tests\UserAchievements;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserAchievement;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class DeleteUserAchievementTest extends DuskTestCase
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

            // And the user has been awarded the achievement at the LAN
            UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'lan_id' => $lan->id,
            ]);

            // And a user with the super admin role exists
            $superAdmin = $this->createSuperAdmin();

            // And the super admin is logged in
            $browser->loginAs($superAdmin);

            // When the super admin visits the achievements page
            $browser->visitRoute('lans.user-achievements.index', ['lan' => $lan]);

            // And clicks the "options" dropdown next to the user's name
            $browser->clickAtXPath(
                '//a[contains(string(), "' . $user->username . '")]//..//..//button[@title="Options"]'
            );

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // And they should see a confirmation message that the achievement has been revoked
            $browser->assertSee(
                'You have revoked the achievement "' . $achievement->name . '" from ' . $user->username
            );
        });
    }
}
