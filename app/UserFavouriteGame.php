<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class UserFavouriteGame extends Model
{
    protected $fillable = [
        'favouriteable_id',
        'favouriteable_type',
    ];

    /**
     * Get all of the owning favouriteable models.
     */
    public function favouriteable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }
}
