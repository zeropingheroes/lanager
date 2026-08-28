<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\EventSignup;
use Zeropingheroes\Lanager\Models\User;

class EventSignupPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can create an item.
     */
    public function create(User $authUser): bool
    {
        return true;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, EventSignup $eventSignup): bool
    {
        // Users can delete their own signups
        return $authUser->id == $eventSignup->user->id;
    }
}
