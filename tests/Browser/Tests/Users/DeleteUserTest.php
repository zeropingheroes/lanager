<?php

namespace Tests\Browser\Tests\Users;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LanAttendees\LanAttendeeIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class DeleteUserTest extends DuskTestCase
{
    /**
     * @return void
     */
    public function testDeletingUser(): void
    {
        $this->browse(function (Browser $browser) {
            // Given a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // And a superadmin exists
            $superAdmin = $this->createSuperAdmin();

            // And the super admin is logged in
            $browser->loginAs($user);

            // When the super admin visits the user's profile page
            $browser->visitRoute('users.show', ['user' => $user]);

            // And clicks the "delete" button
            $browser->press('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // Then they should see a confirmation message that the slide was deleted
            $browser->on(new LanAttendeeIndex())->assertSee('User "' . $user->username . '" deleted');
        });
    }
}
