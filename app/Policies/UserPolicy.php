<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User|null $authUser
     * @param User $requestedUser
     * @return bool
     */
    public function view(?User $authUser, User $requestedUser)
    {
        // Anyone can view users
        return true;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param  \Zeropingheroes\Lanager\Models\User $requestedUser
     * @return bool
     */
    public function delete(User $authUser, User $requestedUser)
    {
        // Non-super admins can only delete their own account
        return $authUser->id == $requestedUser->id;
    }
}
