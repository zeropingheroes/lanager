<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class EventSignup extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Event');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }
}
