<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\LanGameVote;

class LanGameVotePolicy extends BasePolicy
{
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
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param LanGameVote $lanGame
     * @return mixed
     */
    public function delete(User $authUser, LanGameVote $lanGame)
    {
        return $authUser->id == $lanGame->user->id;
    }
}
