<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\RoleAssignment;
use Zeropingheroes\Lanager\Models\User;

class RoleAssignmentPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function index(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param  \Zeropingheroes\Lanager\Models\RoleAssignment $roleAssignment
     * @return bool
     */
    public function view(?User $authUser, RoleAssignment $roleAssignment)
    {
        // Anyone can view a single role assignment
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param  User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param RoleAssignment $roleAssignment
     * @return bool
     */
    public function delete(User $authUser, RoleAssignment $roleAssignment)
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }
}
