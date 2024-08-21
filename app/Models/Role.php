<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/* @mixin Eloquent */
class Role extends Model
{
    /**
     * Users with the role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\User', 'role_assignments')
            ->using('Zeropingheroes\Lanager\Models\RoleAssignment');
    }
}
