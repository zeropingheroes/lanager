<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Slide;
use Zeropingheroes\Lanager\User;

class SlidePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     *
     * @param User|null $authUser
     * @param Slide $slide
     * @return bool
     */
    public function view(?User $authUser, Slide $slide)
    {
        // Admins can view any slide
        if ($authUser && $authUser->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view a slide if the slide and its parent LAN are both published
        return $slide->published && $slide->lan->published;
    }

    /**
     * Determine whether the logged-in user can list all items.
     *
     * @param User $authUser
     * @return bool
     */
    public function index(User $authUser)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can create an item.
     *
     * @param User $authUser
     * @return bool
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
     * @return bool
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
     * @return bool
     */
    public function delete(User $authUser, Slide $slide)
    {
        return $authUser->hasRole('admin');
    }
}
