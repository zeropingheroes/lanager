<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamApp', 'steam_app_id')->withDefault();
    }
}
