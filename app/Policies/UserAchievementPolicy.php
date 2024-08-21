<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserAchievement;

class UserAchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all items.
     */
    public function index(?User $authUser): bool
    {
        return true;
    }

    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, UserAchievement $userAchievement): bool
    {
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
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, UserAchievement $userAchievement): bool
    {
        return $authUser->hasRole('admin');
    }
}
