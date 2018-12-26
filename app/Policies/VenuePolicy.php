<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Venue;

class VenuePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Venue $venue
     * @return mixed
     */
    public function view(?User $user, Venue $venue)
    {
        return true;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param Venue $venue
     * @return mixed
     */
    public function update(User $user, Venue $venue)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param Venue $venue
     * @return mixed
     */
    public function delete(User $user, Venue $venue)
    {
        return $user->hasRole('admin');
    }
}
