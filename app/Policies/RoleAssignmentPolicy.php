<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\User;

class RoleAssignmentPolicy extends BasePolicy
{
    /**
     * Determine whether the user can list all items.
     *
     * @param User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param RoleAssignment $roleAssignment
     * @return mixed
     */
    public function view(?User $user, RoleAssignment $roleAssignment)
    {
        // Anyone can view a single role assignment
        return true;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Only Super Admins can assign / unassign roles (defined in BasePolicy)
        return false;
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param RoleAssignment $roleAssignment
     * @return mixed
     */
    public function delete(User $user, RoleAssignment $roleAssignment)
    {
        // Only Super Admins can assign / unassign roles (defined in BasePolicy)
        return false;
    }
}
