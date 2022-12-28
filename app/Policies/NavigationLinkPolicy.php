<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\NavigationLink;
use Zeropingheroes\Lanager\Models\User;

class NavigationLinkPolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User|null $authUser
     * @param NavigationLink $navigationLink
     * @return bool
     */
    public function view(?User $authUser, NavigationLink $navigationLink)
    {
        // Anyone can view a single navigation link
        return true;
    }

    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function index(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can edit a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param NavigationLink $navigationLink
     * @return bool
     */
    public function update(User $authUser, NavigationLink $navigationLink)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $authUser
     * @param NavigationLink $navigationLink
     * @return bool
     */
    public function delete(User $authUser, NavigationLink $navigationLink)
    {
        return $authUser->hasRole('admin');
    }
}
