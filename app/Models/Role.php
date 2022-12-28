<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/* @mixin Eloquent */
class Role extends Model
{
    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\User', 'role_assignments')
            ->using('Zeropingheroes\Lanager\Models\RoleAssignment');
    }
}
