<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The users who have the role assigned
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\User', 'role_assignments')
            ->using('Zeropingheroes\Lanager\RoleAssignment');
    }
}
