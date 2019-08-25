<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\NavigationLink;

class NavigationLinkPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User $authUser
     * @param NavigationLink $navigationLink
     * @return boolean
     */
    public function view(?User $authUser, NavigationLink $navigationLink)
    {
        // Anyone can view a single navigation link
        return true;
    }

    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param User $authUser
     * @param NavigationLink $navigationLink
     * @return boolean
     */
    public function index(User $authUser, NavigationLink $navigationLink)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return boolean
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param User $authUser
     * @param NavigationLink $navigationLink
     * @return boolean
     */
    public function update(User $authUser, NavigationLink $navigationLink)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param NavigationLink $navigationLink
     * @return boolean
     */
    public function delete(User $authUser, NavigationLink $navigationLink)
    {
        return $authUser->hasRole('admin');
    }
}
