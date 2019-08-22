<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamApp extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public $timestamps = false;

    /**
     * @param string $size
     * @return string
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
     * @return string
     */
    public function url(): string
    {
        return 'steam://store/' . $this->id;
    }

    /**
     * Get all of the Steam app's LAN attendee picks.
     */
    public function picks()
    {
        return $this->morphMany('Zeropingheroes\Lanager\LanAttendeeGamePick', 'game', 'game_provider', 'game_id');
    }
}
