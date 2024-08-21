<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\LanGameVote;
use Zeropingheroes\Lanager\Models\User;

class LanGameVotePolicy extends BasePolicy
{
    /**
     * Determine whether the user can create an item.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete a given item.
     */
    public function delete(User $authUser, LanGameVote $lanGameVote): bool
    {
        return $authUser->id == $lanGameVote->user_id;
    }
}
