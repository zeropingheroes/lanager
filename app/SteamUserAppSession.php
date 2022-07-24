<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class SteamUserAppSession extends Model
{
    protected $fillable = [
        'user_id',
        'steam_app_id',
        'start',
        'end',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    protected $with = [
        'app',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamApp', 'steam_app_id')->withDefault();
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end');
    }
}
