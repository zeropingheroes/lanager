<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class RoleAssignment extends Model
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
     * The user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this
            ->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * The role
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role()
    {
        return $this
            ->belongsTo('Zeropingheroes\Lanager\Role');
    }

    /**
     * The user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function assigner()
    {
        return $this
            ->belongsTo('Zeropingheroes\Lanager\User', 'assigned_by');
    }
}
