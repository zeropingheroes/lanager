<?php

namespace Tests\Browser\Tests\RoleAssignments;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class DeleteRoleAssignmentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testDeletingRoleAssignment()
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

            // And the user has the "admin" role
            $role = Role::where('name', 'admin')->first();
            $user->roles()->attach($role->id, ['assigned_by' => $superAdmin->id]);

            // When the super admin visits the role assignments index page
            $browser->visitRoute('role-assignments.index');

            // And clicks the "options" dropdown next to the user's name
            $browser->clickAtXPath(
                '//a[contains(string(), "' . $user->username . '")]//..//..//button[@title="Options"]'
            );

            // And clicks the "delete" link
            $browser->clickLink('Delete');

            // And accepts the confirmation dialog
            $browser->acceptDialog();

            // And waits for the role assignment index page to load
            $browser->waitForRoute('role-assignments.index');

            // And they should see a confirmation message that the role assignment has been revoked
            $browser->assertSee($user->username . ' is no longer assigned the role ' . $role->display_name);
        });
    }
}
