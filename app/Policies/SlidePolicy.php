<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Slide;

class SlidePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User $authUser
     * @param Slide $slide
     * @return boolean
     */
    public function view(?User $authUser, Slide $slide)
    {
        // Admins can view any slide
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view a slide if the slide and its parent LAN are both published
        return ($slide->published && $slide->lan->published);
    }

    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param User $authUser
     * @return boolean
     */
    public function index(User $authUser)
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
     * @param Slide $slide
     * @return boolean
     */
    public function update(User $authUser, Slide $slide)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     *
     * @param User $authUser
     * @param Slide $slide
     * @return boolean
     */
    public function delete(User $authUser, Slide $slide)
    {
        return $authUser->hasRole('admin');
    }
}
