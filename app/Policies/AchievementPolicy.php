<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\User;

class AchievementPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param  User|null   $authUser
     * @param  Achievement $achievement
     * @return bool
     */
    public function view(?User $authUser, Achievement $achievement)
    {
        // Anyone can view achievements
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param  User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param  User        $authUser
     * @param  Achievement $achievement
     * @return bool
     */
    public function update(User $authUser, Achievement $achievement)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  User        $authUser
     * @param  Achievement $achievement
     * @return bool
     */
    public function delete(User $authUser, Achievement $achievement)
    {
        return $authUser->hasRole('admin');
    }
}
