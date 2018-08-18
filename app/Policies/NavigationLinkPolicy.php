<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\NavigationLink;

class NavigationLinkPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param NavigationLink $navigationLink
     * @return mixed
     */
    public function view(?User $user, NavigationLink $navigationLink)
    {
        // Anyone can view a single navigation link
        return true;
    }

    /**
     * Determine whether the user can list all items.
     *
     * @param User $user
     * @param NavigationLink $navigationLink
     * @return mixed
     */
    public function index(User $user, NavigationLink $navigationLink)
    {
        return $user->hasRole('admin');
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
     * @param NavigationLink $navigationLink
     * @return mixed
     */
    public function update(User $user, NavigationLink $navigationLink)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param NavigationLink $navigationLink
     * @return mixed
     */
    public function delete(User $user, NavigationLink $navigationLink)
    {
        return $user->hasRole('admin');
    }
}
