<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\UserAchievement;
use Zeropingheroes\Lanager\User;

class UserAchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the user can list all items.
     *
     * @param User $user
     * @return mixed
     */
    public function index(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param UserAchievement $userAchievement
     * @return mixed
     */
    public function view(?User $user, UserAchievement $userAchievement)
    {
        return true;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param UserAchievement $userAchievement
     * @return mixed
     */
    public function delete(User $user, UserAchievement $userAchievement)
    {
        return $user->hasRole('admin');
    }
}
