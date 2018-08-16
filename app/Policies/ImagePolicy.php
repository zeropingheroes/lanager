<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;

class ImagePolicy extends BasePolicy
{
    /**
     * Determine whether the user can list all images.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can create images.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update images.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can delete images.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }
}
