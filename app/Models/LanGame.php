<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
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
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User', 'created_by');
    }

    /**
     * @return HasMany
     */
    public function votes()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\LanGameVote');
    }
}
