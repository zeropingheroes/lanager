<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Guide;
use Zeropingheroes\Lanager\User;

class GuidePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Guide $guide
     * @return mixed
     */
    public function view(?User $user, Guide $guide)
    {
        // Admins can view any guide
        if($user && $user->hasRole('Admin')) {
            return true;
        }
        // Non-admins can only view a guide if the
        // guide and its parent LAN are both published
        return ($guide->published && $guide->lan->published);
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
     * @param Guide $guide
     * @return mixed
     */
    public function update(User $user, Guide $guide)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param Guide $guide
     * @return mixed
     */
    public function delete(User $user, Guide $guide)
    {
        return $user->hasRole('Admin');
    }
}
