<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class LanGameVote extends Model
{
    protected $fillable = [
        'lan_game_id',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lanGame()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\LanGame');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }
}
