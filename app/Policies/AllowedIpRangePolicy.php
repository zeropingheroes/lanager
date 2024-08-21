<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\AllowedIpRange;
use Zeropingheroes\Lanager\Models\User;

class AllowedIpRangePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, AllowedIpRange $allowedIpRange): bool
    {
        return $authUser->hasRole('admin');
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
    public function update(User $authUser, AllowedIpRange $allowedIpRange): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, AllowedIpRange $allowedIpRange): bool
    {
        return $authUser->hasRole('admin');
    }
}
