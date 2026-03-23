<?php

namespace Zeropingheroes\Lanager\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Zeropingheroes\Lanager\Models\User;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Run checks before calling the individual policies.
     */
    public function before(?User $authUser)
    {
        // Allow users with the super admin role to perform any action
        if ($authUser instanceof User && $authUser->hasRole('super-admin')) {
            return true;
        }
    }
}
