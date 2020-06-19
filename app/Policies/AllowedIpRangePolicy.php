<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\AllowedIpRange;
use Zeropingheroes\Lanager\User;

class AllowedIpRangePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param AllowedIpRange $allowedIpRange
     * @return bool
     */
    public function view(?User $authUser, AllowedIpRange $allowedIpRange)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param User $authUser
     * @param AllowedIpRange $allowedIpRange
     * @return bool
     */
    public function update(User $authUser, AllowedIpRange $allowedIpRange)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param AllowedIpRange $allowedIpRange
     * @return bool
     */
    public function delete(User $authUser, AllowedIpRange $allowedIpRange)
    {
        return $authUser->hasRole('admin');
    }
}
