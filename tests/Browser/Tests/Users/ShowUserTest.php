<?php

namespace Tests\Browser\Tests\Users;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class ShowUserTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testShowingUser(): void
    {
        $this->browse(function (Browser $browser) {
            // Given a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // When an unathenticated user visits the user's profile page
            $browser->visitRoute('users.show', ['user' => $user]);

            // Then the unathenticated user should see the user's username
            $browser->assertSee($user->username);
        });
    }
}
