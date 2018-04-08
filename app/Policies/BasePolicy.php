<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Allow super admins to perform any action
     *
     * @param $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }
}
