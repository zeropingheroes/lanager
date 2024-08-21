<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class ImagePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all images.
     */
    public function view(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create images.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can update images.
     */
    public function update(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete images.
     */
    public function delete(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
