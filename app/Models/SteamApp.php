<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/* @mixin Eloquent */
class SteamApp extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public $timestamps = false;

    /**
     * Game logo image
     */
    public function logo(string $size = 'small'): string
    {
        return match ($size) {
            'large' => 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header.jpg',
            'medium' => 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header_292x136.jpg',
            'small' => 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/capsule_184x69.jpg',
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
}
