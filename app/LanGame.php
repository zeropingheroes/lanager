<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
class LanGame extends Model
{
    protected $fillable = [
        'lan_id',
        'game_name',
        'created_by',
    ];

    protected $with = [
        'votes.user',
    ];

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'created_by');
    }

    /**
     * @return HasMany
     */
    public function votes()
    {
        return $this->hasMany('Zeropingheroes\Lanager\LanGameVote');
    }
}
