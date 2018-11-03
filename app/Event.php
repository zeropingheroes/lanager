<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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

    protected $dates = [
        'start',
        'end',
        'signups_open',
        'signups_close',
    ];

    protected $with = [
        'lan',
        'type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\EventType', 'event_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function signups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\EventSignup');
    }
}
