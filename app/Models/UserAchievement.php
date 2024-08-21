<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'achievement_id',
        'lan_id',
    ];

    protected $with = [
        'lan',
    ];

    /**
     * User who was awarded the achievement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * Achievement awarded to the user
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Achievement');
    }

    /**
     * LAN the achievement was awarded at
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }
}
