<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * User who has been assigned the role
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Role that has been assigned to the user
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
