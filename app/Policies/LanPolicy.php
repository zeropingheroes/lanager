<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

class LanPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Lan $lan
     * @return mixed
     */
    public function view(?User $user, Lan $lan)
    {
        // Admins can view any LAN
        if($user && $user->hasRole('Admin')) {
            return true;
        }
        // Non-admins can view published LANs
        return $lan->published;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param Lan $lan
     * @return mixed
     */
    public function update(User $user, Lan $lan)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param  User $user
     * @param Lan $lan
     * @return mixed
     */
    public function delete(User $user, Lan $lan)
    {
        return $user->hasRole('Admin');
    }
}
