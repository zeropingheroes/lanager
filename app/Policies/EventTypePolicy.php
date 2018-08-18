<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\EventType;
use Zeropingheroes\Lanager\User;

class EventTypePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param EventType $eventType
     * @return mixed
     */
    public function view(?User $user, EventType $eventType)
    {
        // Anyone can view event types
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
     * @param EventType $eventType
     * @return mixed
     */
    public function update(User $user, EventType $eventType)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param EventType $eventType
     * @return mixed
     */
    public function delete(User $user, EventType $eventType)
    {
        return $user->hasRole('admin');
    }
}
