<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoleAssignmentPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all roleAssignments.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create roleAssignments.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the roleAssignment.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }
}
