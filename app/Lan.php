<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;


class Lan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start',
        'end',
        'published',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
    ];

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
     * Scope a query to only show future LANs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture($query)
    {
        return $query->where('start', '>', now());
    }

    /**
     * Scope a query to only show present and past LANs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePresentAndPast($query)
    {
        return $query->where('start', '<', now());
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
     * The guides for the LAN
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guides()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Guide');
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\User', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }
}
