<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class EpicGame extends Model
{
    protected $fillable = [
        'id',
        'name',
        'url',
    ];

    /**
     * Get all of the game's LAN attendee picks.
     */
    public function picks()
    {
        return $this->morphMany('Zeropingheroes\Lanager\LanAttendeeGamePick', 'game', 'game_id_type', 'game_id');
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @param string $size
     * @return string
     */
    public function logo(string $size = 'small'): string
    {
        switch ($size) {
            case 'large':
                return url('img/epic/460x215.png');
            case 'medium':
                return url('img/epic/292x136.png');
            case 'small':
                return url('img/epic/184x69.png');
            default:
                return $this->logo('small');
        }
    }
}
