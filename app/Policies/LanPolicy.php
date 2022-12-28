<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class LanPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User|null $authUser
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return bool
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
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return bool
     */
    public function update(User $authUser, Lan $lan)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return bool
     */
    public function delete(User $authUser, Lan $lan)
    {
        return $authUser->hasRole('admin');
    }
}
