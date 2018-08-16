<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;

class LogPolicy extends BasePolicy
{
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

    /**
     * Determine whether the user can update log entries.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return false;
    }
}
