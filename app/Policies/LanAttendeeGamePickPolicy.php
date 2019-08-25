<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\LanAttendeeGamePick;

class LanAttendeeGamePickPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param null|User $authUser
     * @param LanAttendeeGamePick $lanAttendeeGamePick
     * @return boolean
     */
    public function view(?User $authUser, LanAttendeeGamePick $lanAttendeeGamePick)
    {
        // Anyone can view
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @param User $gamePickUser
     * @return boolean
     */
    public function create(User $authUser, User $gamePickUser)
    {
        // Users can only create their own
        return $authUser->id === $gamePickUser->id;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param LanAttendeeGamePick $lanAttendeeGamePick
     * @return boolean
     */
    public function delete(User $authUser, LanAttendeeGamePick $lanAttendeeGamePick)
    {
        // Users can only delete their own
        return $authUser->id == $lanAttendeeGamePick->user->id;
    }
}
