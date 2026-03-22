<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\User;

class LanGamePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     */
    public function view(?User $authUser, LanGame $lanGame): mixed
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
     */
    public function create(User $user): true
    {
        return true;
    }

    /**
     * Determine whether the user can edit a given item.
     */
    public function update(User $authUser, LanGame $lanGame): bool
    {
        // Admins can update any
        if ($authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can't update if game already voted for by others
        if ($lanGame->votes()->whereNotIn('user_id', [$authUser->id])->count() > 0) {
            return false;
        }

        // Non-admins can update their own submissions
        return $authUser->id == $lanGame->user->id;
    }

    /**
     * Determine whether the user can delete a given item.
     */
    public function delete(User $authUser, LanGame $lanGame): mixed
    {
        // Same permissions as updating
        return $this->update($authUser, $lanGame);
    }
}
