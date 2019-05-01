<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Slide;

class SlidePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view a given item.
     *
     * @param User $user
     * @param Slide $slide
     * @return mixed
     */
    public function view(?User $user, Slide $slide)
    {
        // admins can view any slide
        if ($user && $user->hasRole('admin')) {
            return true;
        }
        // Non-admins can only view a slide if the
        // slide and its parent LAN are both published
        return ($slide->published && $slide->lan->published);
    }

    /**
     * Determine whether the user can list all items.
     *
     * @param User $user
     * @param Slide $slide
     * @return mixed
     */
    public function index(User $user, Slide $slide)
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
     * @param Slide $slide
     * @return mixed
     */
    public function update(User $user, Slide $slide)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete a given item.
     *
     * @param User $user
     * @param Slide $slide
     * @return mixed
     */
    public function delete(User $user, Slide $slide)
    {
        return $user->hasRole('admin');
    }
}
