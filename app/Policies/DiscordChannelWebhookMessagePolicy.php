<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class DiscordChannelWebhookMessagePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the message compose form.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can send a message.
     */
    public function store(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
