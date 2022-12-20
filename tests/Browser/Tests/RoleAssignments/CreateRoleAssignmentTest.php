<?php

namespace Tests\Browser\Tests\RoleAssignments;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateRoleAssignmentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingRoleAssignment()
    {
        $this->browse(function (Browser $browser) {
            // Given there is a user with the role "super admin"
            $superAdmin = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($superAdmin);

            // And a user exists
            $user = User::factory()
                ->has(
                    UserOAuthAccount::factory()->count(1),
                    'accounts'
                )
                ->create();

            // When the super admin visits the role assignments index page
            $browser->visitRoute('role-assignments.index');

            // And waits for the user dropdown to load
            $browser->waitFor('select#user_id');

            // And selects the user in the user dropdown
            $browser->select('user_id', $user->username);

            // And selects the role in the role dropdown
            $browser->select('role_id', 'Admin');

            // And clicks the assign role button
            $browser->press('Assign Role');

            // And waits for the event's page to load
            $browser->waitForRoute('role-assignments.index');

            // Then they should see the user's name inside the table of assigned roles
            $browser->assertSeeIn('table', $user->username);
        });
    }
}
