<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\EventSignup;
use Zeropingheroes\Lanager\User;

class EventSignupPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     * @internal param Event $event
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param EventSignup $eventSignup
     * @return mixed
     */
    public function delete(User $user, EventSignup $eventSignup)
    {
        // Users can delete their own signups
        return $user->id == $eventSignup->user->id;
    }
}
