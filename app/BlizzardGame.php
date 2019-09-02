<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class BlizzardGame extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Get all of the game's LAN attendee picks.
     */
    public function picks()
    {
        return $this->morphMany('Zeropingheroes\Lanager\LanAttendeeGamePick', 'game', 'game_id_type', 'game_id');
    }
}
