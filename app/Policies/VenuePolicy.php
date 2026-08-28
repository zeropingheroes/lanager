<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\Venue;

class VenuePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Venue $venue): bool
    {
        return true;
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
    public function update(User $authUser, Venue $venue): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Venue $venue): bool
    {
        return $authUser->hasRole('admin');
    }
}
