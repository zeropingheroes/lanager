<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\LanAttendeeGamePick;

class LanAttendeeGamePickPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param null|User $authUser
     * @param LanAttendeeGamePick $lanAttendeeGamePick
     * @return mixed
     */
    public function view(?User $authUser, LanAttendeeGamePick $lanAttendeeGamePick)
    {
        // Anyone can view
        return true;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $authUser
     * @param User $user
     * @return mixed
     */
    public function create(User $authUser, User $user)
    {
        // Users can only create their own
        return $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $authUser
     * @param LanAttendeeGamePick $lanAttendeeGamePick
     * @return mixed
     */
    public function delete(User $authUser, LanAttendeeGamePick $lanAttendeeGamePick)
    {
        // Users can only delete their own
        return $authUser->id == $lanAttendeeGamePick->user->id;
    }
}
