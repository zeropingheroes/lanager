<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/* @mixin Eloquent */

class RoleAssignment extends Pivot
{
    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_by',
    ];

    protected $table = 'role_assignments';

    /**
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return belongsTo
     */
    public function role()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Role');
    }

    /**
     * @return belongsTo
     */
    public function assigner()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'assigned_by');
    }
}
