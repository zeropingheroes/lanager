<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\EventSignup;
use Zeropingheroes\Lanager\Models\User;

class EventSignupPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return true;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param  \Zeropingheroes\Lanager\Models\EventSignup $eventSignup
     * @return bool
     */
    public function delete(User $authUser, EventSignup $eventSignup)
    {
        // Users can delete their own signups
        return $authUser->id == $eventSignup->user->id;
    }
}
