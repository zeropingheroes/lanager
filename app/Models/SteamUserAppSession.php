<?php

namespace Zeropingheroes\Lanager\Models;

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
