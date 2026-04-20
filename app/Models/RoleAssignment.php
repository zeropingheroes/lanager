<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property int|null $assigned_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $assigner
 * @property-read Role $role
 * @property-read User $user
 *
 * @method static Builder<static>|RoleAssignment newModelQuery()
 * @method static Builder<static>|RoleAssignment newQuery()
 * @method static Builder<static>|RoleAssignment query()
 * @method static Builder<static>|RoleAssignment whereAssignedBy($value)
 * @method static Builder<static>|RoleAssignment whereCreatedAt($value)
 * @method static Builder<static>|RoleAssignment whereId($value)
 * @method static Builder<static>|RoleAssignment whereRoleId($value)
 * @method static Builder<static>|RoleAssignment whereUpdatedAt($value)
 * @method static Builder<static>|RoleAssignment whereUserId($value)
 *
 * @mixin Eloquent
 */
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
