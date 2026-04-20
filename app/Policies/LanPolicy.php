<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class LanPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Lan $lan): bool
    {
        // Admins can view any LAN
        if ($authUser instanceof User && $authUser->hasRole('admin')) {
            return true;
        }

        // Non-admins can view published LANs
        return (bool) $lan->published;
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
    public function update(User $authUser, Lan $lan): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Lan $lan): bool
    {
        return $authUser->hasRole('admin');
    }
}
