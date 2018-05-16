<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all items.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can create the item.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can edit the item.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the item.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }
}
