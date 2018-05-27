<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start',
        'end',
        'published',
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
     * Scope a query to only show LANs happening now.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHappeningNow($query)
    {
        return $query->where('start', '<', now())
            ->where('end', '>', now());
    }

    /**
     * Scope a query to only show past LANs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end', '<', now());
    }

    /**
     * The events for the LAN
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Event');
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\User', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\LanAttendee')
            ->as('attendance')
            ->withTimestamps();
    }
}
