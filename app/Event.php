<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lan_id',
        'event_type_id',
        'name',
        'description',
        'published',
        'start',
        'end',
        'signup_opens',
        'signup_closes',
    ];

    /**
     * The event type
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function type()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\EventType');
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
