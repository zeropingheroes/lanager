<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\Venue;

class VenuePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param Venue $venue
     * @return bool
     */
    public function view(?User $authUser, Venue $venue)
    {
        return true;
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
     * @param Venue $venue
     * @return bool
     */
    public function update(User $authUser, Venue $venue)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  User  $authUser
     * @param  Venue $venue
     * @return bool
     */
    public function delete(User $authUser, Venue $venue)
    {
        return $authUser->hasRole('admin');
    }
}
