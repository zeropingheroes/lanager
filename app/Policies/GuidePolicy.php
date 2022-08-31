<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\User;

class GuidePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param  \Zeropingheroes\Lanager\Models\Guide $guide
     * @return bool
     */
    public function view(?User $authUser, Guide $guide)
    {
        // admins can view any guide
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view a guide if the
        // guide and its parent LAN are both published
        return $guide->published && $guide->lan->published;
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
     * @param Guide $guide
     * @return bool
     */
    public function update(User $authUser, Guide $guide)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param Guide $guide
     * @return bool
     */
    public function delete(User $authUser, Guide $guide)
    {
        return $authUser->hasRole('admin');
    }
}
