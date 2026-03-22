<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\User;

class GuidePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Guide $guide): bool
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
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     */
    public function update(User $authUser, Guide $guide): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Guide $guide): bool
    {
        return $authUser->hasRole('admin');
    }
}
