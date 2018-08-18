<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_activity',
    ];

    /**
     * Get the user who the session belongs to
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }
}
