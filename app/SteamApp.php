<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamApp extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    public $timestamps = false;

    /**
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
     * @return string
     */
    public function steamStoreURL(): string
    {
        return 'steam://store/' . $this->id;
    }
}
