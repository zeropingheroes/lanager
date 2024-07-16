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
        switch ($size) {
            case 'large':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header.jpg'; // 460x215
            case 'medium':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header_292x136.jpg';
            case 'small':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/capsule_184x69.jpg';

            default:
                return $this->logo('small');
        }
    }

    /**
     * URL to open game in Steam app store
     */
    public function url(): string
    {
        return 'steam://store/' . $this->id;
    }
}
