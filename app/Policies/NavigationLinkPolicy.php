<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\NavigationLink;
use Illuminate\Auth\Access\HandlesAuthorization;

class NavigationLinkPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list all navigation links.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return mixed
     */
    public function index(NavigationLink $navigationLink)
    {
        return false;
    }

    /**
     * Determine whether the user can create navigation links.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return mixed
     */
    public function create(NavigationLink $navigationLink)
    {
        return false;
    }

    /**
     * Determine whether the user can update navigation links.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return mixed
     */
    public function update(NavigationLink $navigationLink)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the navigation link.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return mixed
     */
    public function delete(NavigationLink $navigationLink)
    {
        return false;
    }
}
