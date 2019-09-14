<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class OriginGame extends Model
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
        return '';
    }
}
