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
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\SteamApp', 'steam_app_id')->withDefault();
    }
}
