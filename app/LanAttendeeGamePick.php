<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class LanAttendeeGamePick extends Model
{
    protected $fillable = [
        'user_id',
        'lan_id',
        'game_id',
        'game_id_type',
    ];

    /**
     * Get all of the owning game models.
     */
    public function game()
    {
        return $this->morphTo(null, 'game_id_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }
}
