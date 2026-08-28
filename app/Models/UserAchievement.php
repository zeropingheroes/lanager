<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $achievement_id
 * @property int $lan_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Achievement $achievement
 * @property-read Lan $lan
 * @property-read User $user
 *
 * @method static Builder<static>|UserAchievement newModelQuery()
 * @method static Builder<static>|UserAchievement newQuery()
 * @method static Builder<static>|UserAchievement query()
 * @method static Builder<static>|UserAchievement whereAchievementId($value)
 * @method static Builder<static>|UserAchievement whereCreatedAt($value)
 * @method static Builder<static>|UserAchievement whereId($value)
 * @method static Builder<static>|UserAchievement whereLanId($value)
 * @method static Builder<static>|UserAchievement whereUpdatedAt($value)
 * @method static Builder<static>|UserAchievement whereUserId($value)
 *
 * @mixin Eloquent
 */
class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'achievement_id',
        'lan_id',
    ];

    protected $with = [
        'lan',
    ];

    /**
     * User who was awarded the achievement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Achievement awarded to the user
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * LAN the achievement was awarded at
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }
}
