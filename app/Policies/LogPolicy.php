<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all log entries.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return false;
    }
}
