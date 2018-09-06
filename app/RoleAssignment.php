<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleAssignment extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_by',
    ];

    /**
     * The table that this model uses
     *
     * @var array
     */
    protected $table = 'role_assignments';

    /**
     * The user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * The role
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Role');
    }

    /**
     * The user who assigned the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function assigner()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'assigned_by');
    }
}
