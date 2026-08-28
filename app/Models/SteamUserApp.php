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
 * @property int|null $steam_app_id
 * @property int $playtime_two_weeks
 * @property int $playtime_forever
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SteamApp|null $app
 * @property-read User $user
 *
 * @method static Builder<static>|SteamUserApp newModelQuery()
 * @method static Builder<static>|SteamUserApp newQuery()
 * @method static Builder<static>|SteamUserApp query()
 * @method static Builder<static>|SteamUserApp whereCreatedAt($value)
 * @method static Builder<static>|SteamUserApp whereId($value)
 * @method static Builder<static>|SteamUserApp wherePlaytimeForever($value)
 * @method static Builder<static>|SteamUserApp wherePlaytimeTwoWeeks($value)
 * @method static Builder<static>|SteamUserApp whereSteamAppId($value)
 * @method static Builder<static>|SteamUserApp whereUpdatedAt($value)
 * @method static Builder<static>|SteamUserApp whereUserId($value)
 *
 * @mixin Eloquent
 */
class SteamUserApp extends Model
{
    protected $fillable = [
        'user_id',
        'steam_app_id',
        'playtime_two_weeks',
        'playtime_forever',
    ];

    /**
     * User who has the app in their library
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * App in the user's library
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(SteamApp::class, 'steam_app_id')->withDefault();
    }
}
