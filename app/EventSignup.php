<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class EventSignup extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }
}
