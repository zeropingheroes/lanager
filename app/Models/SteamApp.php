<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
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
            'Zeropingheroes\Lanager\Models\SteamUserAppSession'
        );
    }

    /**
     * Players of the app
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(
            'Zeropingheroes\Lanager\Models\User',
            'steam_user_app_sessions',
        );
    }

    /**
     * Users who own the app
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(
            'Zeropingheroes\Lanager\Models\User',
            'steam_user_apps',
        );
    }
}
