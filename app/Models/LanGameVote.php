<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

/* @mixin Eloquent */
class LanGameVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_game_id',
        'user_id',
    ];

    /**
     * @return belongsTo
     */
    public function lanGame()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\LanGame');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }
}