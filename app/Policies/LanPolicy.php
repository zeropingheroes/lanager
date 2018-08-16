<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

class LanPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Lan $lan
     * @return mixed
     */
    public function view(?User $user, Lan $lan)
    {
        return $lan->published;
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
     * @param Lan $lan
     * @return mixed
     */
    public function update(User $user, Lan $lan)
    {
        return false;
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param  User $user
     * @param Lan $lan
     * @return mixed
     */
    public function delete(User $user, Lan $lan)
    {
        return false;
    }
}
