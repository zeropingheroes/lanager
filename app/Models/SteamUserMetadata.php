<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $steam_user_status_code_id
 * @property int|null $profile_visible
 * @property int|null $apps_visible
 * @property Carbon|null $profile_updated_at
 * @property Carbon|null $apps_updated_at
 * @property-read SteamUserStatusCode $status
 * @property-read User $user
 *
 * @method static Builder<static>|SteamUserMetadata newModelQuery()
 * @method static Builder<static>|SteamUserMetadata newQuery()
 * @method static Builder<static>|SteamUserMetadata query()
 * @method static Builder<static>|SteamUserMetadata whereAppsUpdatedAt($value)
 * @method static Builder<static>|SteamUserMetadata whereAppsVisible($value)
 * @method static Builder<static>|SteamUserMetadata whereId($value)
 * @method static Builder<static>|SteamUserMetadata whereProfileUpdatedAt($value)
 * @method static Builder<static>|SteamUserMetadata whereProfileVisible($value)
 * @method static Builder<static>|SteamUserMetadata whereSteamUserStatusCodeId($value)
 * @method static Builder<static>|SteamUserMetadata whereUserId($value)
 *
 * @mixin Eloquent
 */
class SteamUserMetadata extends Model
{
    protected $fillable = [
        'user_id',
        'steam_user_status_code_id',
        'profile_visible',
        'apps_visible',
        'profile_updated_at',
        'apps_updated_at',
    ];

    protected $casts = [
        'profile_updated_at' => 'datetime',
        'apps_updated_at' => 'datetime',
    ];

    protected $table = 'steam_user_metadata';

    public $timestamps = false;

    /**
     * User who the metadata record belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status code of the user
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(SteamUserStatusCode::class, 'steam_user_status_code_id');
    }
}
