<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
     * Scope a query to only show items visible to the user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        if (Auth::user() && Auth::user()->can('update', $this)) {
            return $query;
        } else {
            return $query->where('published', '=', 1);
        }
    }

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
