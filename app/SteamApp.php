<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamApp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     *  Get the app's image URL
     *
     * @param string $size
     * @return string
     */
    public function image(string $size = 'small'): string
    {
        switch ($size) {
            case 'large':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header.jpg'; // 460x215
            case 'medium':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/header_292x136.jpg';
            case 'small':
                return 'http://cdn.akamai.steamstatic.com/steam/apps/' . $this->id . '/capsule_184x69.jpg';

            default:
                return $this->image('small');
        }
    }

    /**
     * Get the app's Steam Store URL
     * @return string
     */
    public function steamStoreURL(): string
    {
        return 'steam://store/' . $this->id;
    }
}
