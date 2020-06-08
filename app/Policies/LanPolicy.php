<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

class LanPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param Lan $lan
     * @return boolean
     */
    public function view(?User $authUser, Lan $lan)
    {
        // Admins can view any LAN
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can view published LANs
        return $lan->published;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return boolean
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param User $authUser
     * @param Lan $lan
     * @return boolean
     */
    public function update(User $authUser, Lan $lan)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param Lan $lan
     * @return boolean
     */
    public function delete(User $authUser, Lan $lan)
    {
        return $authUser->hasRole('admin');
    }
}
