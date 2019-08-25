<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Venue;

class VenuePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User $authUser
     * @param Venue $venue
     * @return boolean
     */
    public function view(?User $authUser, Venue $venue)
    {
        return true;
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
     * @param Venue $venue
     * @return boolean
     */
    public function update(User $authUser, Venue $venue)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param Venue $venue
     * @return boolean
     */
    public function delete(User $authUser, Venue $venue)
    {
        return $authUser->hasRole('admin');
    }
}
