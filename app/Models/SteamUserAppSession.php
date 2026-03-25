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
 * @property int $steam_app_id
 * @property Carbon $start
 * @property Carbon|null $end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SteamApp $app
 * @property-read User $user
 *
 * @method static Builder<static>|SteamUserAppSession active()
 * @method static Builder<static>|SteamUserAppSession newModelQuery()
 * @method static Builder<static>|SteamUserAppSession newQuery()
 * @method static Builder<static>|SteamUserAppSession query()
 * @method static Builder<static>|SteamUserAppSession whereCreatedAt($value)
 * @method static Builder<static>|SteamUserAppSession whereEnd($value)
 * @method static Builder<static>|SteamUserAppSession whereId($value)
 * @method static Builder<static>|SteamUserAppSession whereStart($value)
 * @method static Builder<static>|SteamUserAppSession whereSteamAppId($value)
 * @method static Builder<static>|SteamUserAppSession whereUpdatedAt($value)
 * @method static Builder<static>|SteamUserAppSession whereUserId($value)
 *
 * @mixin Eloquent
 */
class SteamUserAppSession extends Model
{
    protected $fillable = [
        'user_id',
        'steam_app_id',
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $with = [
        'app',
    ];

    /**
     * User who had the session in the app (played the game)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * App the user had the session in (game played)
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(SteamApp::class, 'steam_app_id')->withDefault();
    }

    /**
     * Active sessions (have not yet ended)
     */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->whereNull('end');
    }
}
