<?php

namespace Tests\Browser\Tests\RoleAssignments;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class IndexRoleAssignmentsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testIndexingRoleAssignments()
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

            // When the super admin visits the home page
            $browser->visit('/');

            // And they click the admin menu
            $browser->click('#admin-menu');

            // And they click the "role assignments" menu item
            $browser->clickLink('Role Assignments');

            // And they wait for the role assignments index page to load
            $browser->waitForRoute('role-assignments.index');

            // Then they should see the user's username in the table
            $browser->assertSeeIn('table', $user->username);
        });
    }
}
