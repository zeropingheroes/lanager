<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\User;

class AchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Achievement $achievement
     * @return mixed
     */
    public function view(?User $user, Achievement $achievement)
    {
        // Anyone can view achievements
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
     * Determine whether the user can edit a given item.
     *
     * @param User $user
     * @param Achievement $achievement
     * @return mixed
     */
    public function update(User $user, Achievement $achievement)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param Achievement $achievement
     * @return mixed
     */
    public function delete(User $user, Achievement $achievement)
    {
        return $user->hasRole('admin');
    }
}
