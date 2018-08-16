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
        return $event->published;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
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
        return false;
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
        return false;
    }
}
