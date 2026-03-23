<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\User;

class EventPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Event $event): bool
    {
        // Admins can view any event
        if ($authUser instanceof User && $authUser->hasRole('admin')) {
            return true;
        }

        // Non-admins can only view an event if the event and its parent LAN are both published
        return $event->published && $event->lan->published;
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
    public function update(User $authUser, Event $event): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Event $event): bool
    {
        return $authUser->hasRole('admin');
    }
}
