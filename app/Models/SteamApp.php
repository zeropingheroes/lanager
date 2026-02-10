<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public $timestamps = false;

    /**
     * Game logo image
     */
    public function logo(string $size = 'small'): string
    {
        return match ($size) {
            'large' => 'https://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header.jpg',
            'medium' => 'https://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header_292x136.jpg',
            'small' => 'https://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/capsule_184x69.jpg',
            default => $this->logo('small'),
        };
    }

    /**
     * URL to open game in Steam app store
     */
    public function url(): string
    {
        return 'steam://store/' . $this->id;
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
