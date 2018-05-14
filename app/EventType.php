<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'colour',
    ];

    /**
     * The events with the event type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Event');
    }
}
