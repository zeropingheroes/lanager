<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamUserVisibility extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'profile_visible',
        'apps_visible',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User')
            ->withTimestamps();
    }
}
