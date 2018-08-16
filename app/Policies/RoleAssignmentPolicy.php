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
        return false;
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
        return false;
    }
}
