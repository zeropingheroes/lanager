<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
class LanGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_id',
        'game_name',
        'created_by',
    ];

    protected $with = [
        'votes.user',
    ];

    /**
     * LAN that the game suggestion belongs to
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }

    /**
     * User who suggested the game
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Votes for the game
     */
    public function votes(): HasMany
    {
        return $this->hasMany(LanGameVote::class);
    }
}
