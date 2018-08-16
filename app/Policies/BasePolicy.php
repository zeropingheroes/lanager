<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Run checks before calling the individual policies
     *
     * @param $user
     * @return bool
     */
    public function before(User $user)
    {
        // Allow users with the Super Admin role
        // to perform any action
        return $user->hasRole('Super Admin');
    }
}
