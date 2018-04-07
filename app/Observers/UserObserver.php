<?php

namespace Zeropingheroes\Lanager\Observers;

use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Role;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {
        // The first user to log in should be assigned the super admin role
        if (User::count() == 1) {
            $user->roles()->attach(Role::where('name', 'Super Admin')->first());
        }
    }
}