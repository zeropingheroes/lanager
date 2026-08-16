<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class EventDiscordNotificationMessagePolicy extends BasePolicy
{
    /**
     * Determine whether the user can set an event's Discord notification message.
     */
    public function update(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can manually send an event's Discord notification message.
     */
    public function send(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can preview a Discord notification message.
     */
    public function preview(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
