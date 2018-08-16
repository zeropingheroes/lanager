<?php

namespace Zeropingheroes\Lanager\Policies;

use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can delete the page.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return Auth::user()->id === $user->id;
    }
}
