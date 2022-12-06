<?php

namespace Tests\Browser\Tests\UserAchievements;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserAchievement;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class IndexUserAchievementTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testIndexingUserAchievements()
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

            // When an unauthenticated user visits the LAN's achievements page
            $browser->visitRoute('lans.user-achievements.index', ['lan' => $lan]);

            // Then the user's name should show inside the table of awarded achievements
            $browser->assertSeeIn('.table', $user->username);

            // And the achievement name should show inside the table of awarded achievements
            $browser->assertSeeIn('.table', $achievement->name);
        });
    }
}
