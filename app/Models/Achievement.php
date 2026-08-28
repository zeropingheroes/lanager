<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image_filename
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, UserAchievement> $users
 * @property-read int|null $users_count
 *
 * @method static Builder<static>|Achievement newModelQuery()
 * @method static Builder<static>|Achievement newQuery()
 * @method static Builder<static>|Achievement query()
 * @method static Builder<static>|Achievement whereCreatedAt($value)
 * @method static Builder<static>|Achievement whereDescription($value)
 * @method static Builder<static>|Achievement whereId($value)
 * @method static Builder<static>|Achievement whereImageFilename($value)
 * @method static Builder<static>|Achievement whereName($value)
 * @method static Builder<static>|Achievement whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_filename',
    ];

    /**
     * Users who have been awarded the achievement
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}
