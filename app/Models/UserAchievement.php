<?php

namespace Zeropingheroes\Lanager\Models;

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
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * @return belongsTo
     */
    public function achievement()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Achievement');
    }

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }
}
