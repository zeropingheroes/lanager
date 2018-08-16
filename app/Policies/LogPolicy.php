<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Log;
use Zeropingheroes\Lanager\User;

class LogPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Log $log
     * @return mixed
     */
    public function view(User $user, Log $log)
    {
        return false;
    }

    /**
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param Log $log
     * @return mixed
     */
    public function update(User $user, Log $log)
    {
        return false;
    }
}
