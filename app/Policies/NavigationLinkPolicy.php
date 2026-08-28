<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\NavigationLink;
use Zeropingheroes\Lanager\Models\User;

class NavigationLinkPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, NavigationLink $navigationLink): bool
    {
        // Anyone can view a single navigation link
        return true;
    }

    /**
     * Determine whether the logged-in user can list all items.
     */
    public function index(User $authUser): bool
    {
        return $authUser->hasRole('admin');
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
    public function update(User $authUser, NavigationLink $navigationLink): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, NavigationLink $navigationLink): bool
    {
        return $authUser->hasRole('admin');
    }
}
