<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class LanGameVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_game_id',
        'user_id',
    ];

    /**
     * Game the vote is for
     */
    public function lanGame(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\LanGame');
    }

    /**
     * User who casted the vote
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }
}
