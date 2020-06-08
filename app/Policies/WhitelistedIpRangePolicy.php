<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\WhitelistedIpRange;

class WhitelistedIpRangePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return boolean
     */
    public function view(?User $authUser, WhitelistedIpRange $whitelistedIpRange)
    {
        return $authUser->hasRole('admin');
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
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return boolean
     */
    public function update(User $authUser, WhitelistedIpRange $whitelistedIpRange)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return boolean
     */
    public function delete(User $authUser, WhitelistedIpRange $whitelistedIpRange)
    {
        return $authUser->hasRole('admin');
    }
}
