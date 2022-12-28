<?php

namespace Zeropingheroes\Lanager\Observers;

use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \Zeropingheroes\Lanager\Models\User $user
     * @return void
     */
    public function created(User $user)
    {
        // The first user to log in should be assigned the super admin role
        if (User::count() == 1) {
            $role = Role::where('name', 'super-admin')->first();
            $user->roles()->attach($role->id, ['assigned_by' => $user->id]);
        }
    }
}
