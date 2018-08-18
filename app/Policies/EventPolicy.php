<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\User;
class EventPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Event $event
     * @return mixed
     */
    public function view(?User $user, Event $event)
    {
        // Admins can view any event
        if($user && $user->hasRole('Admin')) {
            return true;
        }
        // Non-admins can only view an event if the
        // event and its parent LAN are both published
        return ($event->published && $event->lan->published);
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param Event $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param  User $user
     * @param Event $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $user->hasRole('Admin');
    }
}
