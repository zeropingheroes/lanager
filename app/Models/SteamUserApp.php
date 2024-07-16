<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
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
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * App in the user's library
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\SteamApp', 'steam_app_id')->withDefault();
    }
}
