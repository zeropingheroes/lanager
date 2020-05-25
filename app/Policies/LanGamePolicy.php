<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\LanGame;

class LanGamePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param LanGame $lanGame
     * @return mixed
     */
    public function view(?User $authUser, LanGame $lanGame)
    {
        // Admins can view any
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view if the LAN has been published
        return $lanGame->lan->published;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param LanGame $lanGame
     * @return mixed
     */
    public function update(User $authUser, LanGame $lanGame)
    {
        return $authUser->id == $lanGame->user->id;
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param LanGame $lanGame
     * @return mixed
     */
    public function delete(User $authUser, LanGame $lanGame)
    {
        return $authUser->id == $lanGame->user->id;
    }
}
