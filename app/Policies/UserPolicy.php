<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param null|User $authUser
     * @param User $requestedUser
     * @return mixed
     */
    public function view(?User $authUser, User $requestedUser)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the page.
     *
     * @param User $authUser
     * @param User $requestedUser
     * @return mixed
     */
    public function delete(User $authUser, User $requestedUser)
    {
        return $authUser->id === $requestedUser->id;
    }
}
