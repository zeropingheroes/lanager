<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, User $requestedUser): bool
    {
        // Anyone can view users
        return true;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, User $requestedUser): bool
    {
        // Non-super admins can only delete their own account
        return $authUser->id == $requestedUser->id;
    }
}
