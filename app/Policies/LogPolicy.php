<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Log;
use Zeropingheroes\Lanager\User;

class LogPolicy extends BasePolicy
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
     * @param Log $log
     * @return boolean
     */
    public function view(User $authUser, Log $log)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param User $authUser
     * @param Log $log
     * @return boolean
     */
    public function update(User $authUser, Log $log)
    {
        return $authUser->hasRole('admin');
    }
}
