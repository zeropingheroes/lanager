<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $logo_small
 * @property string|null $logo_medium
 * @property string|null $logo_large
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, User> $owners
 * @property-read int|null $owners_count
 * @property-read Collection<int, User> $players
 * @property-read int|null $players_count
 * @property-read Collection<int, SteamUserAppSession> $sessions
 * @property-read int|null $sessions_count
 *
 * @method static Builder<static>|SteamApp newModelQuery()
 * @method static Builder<static>|SteamApp newQuery()
 * @method static Builder<static>|SteamApp query()
 * @method static Builder<static>|SteamApp whereCreatedAt($value)
 * @method static Builder<static>|SteamApp whereId($value)
 * @method static Builder<static>|SteamApp whereLogoLarge($value)
 * @method static Builder<static>|SteamApp whereLogoMedium($value)
 * @method static Builder<static>|SteamApp whereLogoSmall($value)
 * @method static Builder<static>|SteamApp whereName($value)
 * @method static Builder<static>|SteamApp whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class SteamApp extends Model
{
    protected $fillable = [
        'id',
        'name',
        'logo_small',
        'logo_medium',
        'logo_large',
    ];

    /**
     * URL to open game in Steam app store
     */
    public function url(): string
    {
        return 'steam://store/'.$this->id;
    }

    /**
     * Player sessions for the app
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(
            SteamUserAppSession::class
        );
    }

    /**
     * Players of the app
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'steam_user_app_sessions',
        );
    }

    /**
     * Users who own the app
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'steam_user_apps',
        );
    }
}
