<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserAchievement;

class UserAchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param User|null $authUser
     * @return bool
     */
    public function index(?User $authUser)
    {
        return true;
    }

    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param UserAchievement $userAchievement
     * @return bool
     */
    public function view(?User $authUser, UserAchievement $userAchievement)
    {
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param UserAchievement $userAchievement
     * @return bool
     */
    public function delete(User $authUser, UserAchievement $userAchievement)
    {
        return $authUser->hasRole('admin');
    }
}
