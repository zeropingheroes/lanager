<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\User;

class AchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Achievement $achievement): bool
    {
        // Anyone can view achievements
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     */
    public function update(User $authUser, Achievement $achievement): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Achievement $achievement): bool
    {
        return $authUser->hasRole('admin');
    }
}
