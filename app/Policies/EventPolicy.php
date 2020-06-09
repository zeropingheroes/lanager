<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\User;

class EventPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param Event $event
     * @return bool
     */
    public function view(?User $authUser, Event $event)
    {
        // Admins can view any event
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view an event if the event and its parent LAN are both published
        return $event->published && $event->lan->published;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param User $authUser
     * @param Event $event
     * @return bool
     */
    public function update(User $authUser, Event $event)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param Event $event
     * @return bool
     */
    public function delete(User $authUser, Event $event)
    {
        return $authUser->hasRole('admin');
    }
}
