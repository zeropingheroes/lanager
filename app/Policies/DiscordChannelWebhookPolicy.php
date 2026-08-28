<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class DiscordChannelWebhookPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the list of webhooks.
     */
    public function index(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can create a webhook.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a webhook.
     */
    public function delete(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
