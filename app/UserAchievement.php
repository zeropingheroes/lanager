<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'achievement_id',
        'lan_id',
    ];

    /**
     * The user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * The achievement
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function achievement()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Achievement');
    }

    /**
     * The LAN
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }
}
