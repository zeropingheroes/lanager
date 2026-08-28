<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\RoleAssignment;
use Zeropingheroes\Lanager\Models\User;

class RoleAssignmentPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can list all items.
     */
    public function index(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, RoleAssignment $roleAssignment): bool
    {
        // Anyone can view a single role assignment
        return true;
    }

    /**
     * Determine whether the logged-in user can create an item.
     */
    public function create(User $authUser): bool
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, RoleAssignment $roleAssignment): bool
    {
        // Only super admins can assign and unassign roles (defined in BasePolicy)
        return false;
    }
}
