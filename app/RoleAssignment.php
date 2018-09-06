<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleAssignment extends Pivot
{
    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_by',
    ];

    protected $table = 'role_assignments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function assigner()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'assigned_by');
    }
}
