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
        'signups_open',
        'signups_close',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
        'signups_open',
        'signups_close',
    ];

    /**
     * The event type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\EventType', 'event_type_id');
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

    /**
     * The event's signups
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function signups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\EventSignup');
    }
}
