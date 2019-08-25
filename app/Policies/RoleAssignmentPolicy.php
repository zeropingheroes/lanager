<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\User;

class RoleAssignmentPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param User $authUser
     * @return boolean
     */
    public function index(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User $authUser
     * @param RoleAssignment $roleAssignment
     * @return boolean
     */
    public function view(?User $authUser, RoleAssignment $roleAssignment)
    {
        // Anyone can view a single role assignment
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return boolean
     */
    public function create(User $authUser)
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param RoleAssignment $roleAssignment
     * @return boolean
     */
    public function delete(User $authUser, RoleAssignment $roleAssignment)
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }
}
