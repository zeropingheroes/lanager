<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\LanGameVote;
use Zeropingheroes\Lanager\Models\User;

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
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param  \Zeropingheroes\Lanager\Models\LanGameVote $lanGameVote
     * @return mixed
     */
    public function delete(User $authUser, LanGameVote $lanGameVote)
    {
        return $authUser->id == $lanGameVote->user_id;
    }
}
