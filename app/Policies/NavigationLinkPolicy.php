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
        return false;
    }

    /**
     * Determine whether the user can create an item.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
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
        return false;
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
        return false;
    }
}
