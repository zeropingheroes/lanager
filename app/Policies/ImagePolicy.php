<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class ImagePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all images.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function view(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create images.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can update images.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function update(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete images.
     *
     * @param  User $authUser
     * @return bool
     */
    public function delete(User $authUser)
    {
        return $authUser->hasRole('admin');
    }
}
