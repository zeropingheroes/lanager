<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

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
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return belongsTo
     */
    public function achievement()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Achievement');
    }

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }
}
