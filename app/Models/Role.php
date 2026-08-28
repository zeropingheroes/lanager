<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read RoleAssignment|null $pivot
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static Builder<static>|Role newModelQuery()
 * @method static Builder<static>|Role newQuery()
 * @method static Builder<static>|Role query()
 * @method static Builder<static>|Role whereCreatedAt($value)
 * @method static Builder<static>|Role whereDisplayName($value)
 * @method static Builder<static>|Role whereId($value)
 * @method static Builder<static>|Role whereName($value)
 * @method static Builder<static>|Role whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Role extends Model
{
    /**
     * Users with the role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->using(RoleAssignment::class);
    }
}
