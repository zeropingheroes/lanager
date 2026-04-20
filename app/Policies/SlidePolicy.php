<?php

namespace Zeropingheroes\Lanager\Policies;

use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;

class SlidePolicy extends BasePolicy
{
    /**
     * Determine whether the logged-in user can view a given item.
     */
    public function view(?User $authUser, Slide $slide): bool
    {
        // Admins can view any slide
        if ($authUser instanceof User && $authUser->hasRole('admin')) {
            return true;
        }

        // Non-admins can only view a slide if the slide and its parent LAN are both published
        return $slide->published && $slide->lan->published;
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
    public function update(User $authUser, Slide $slide): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the logged-in user can delete a given item.
     */
    public function delete(User $authUser, Slide $slide): bool
    {
        return $authUser->hasRole('admin');
    }
}
