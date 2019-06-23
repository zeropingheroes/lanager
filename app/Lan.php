<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;


class Lan extends Model
{
    protected $fillable = [
        'venue_id',
        'achievement_id',
        'name',
        'description',
        'start',
        'end',
        'published',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHappeningNow($query)
    {
        return $query->where('start', '<', now())
            ->where('end', '>', now());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end', '<', now());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture($query)
    {
        return $query->where('start', '>', now());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePresentAndPast($query)
    {
        return $query->where('start', '<', now());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guides()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Guide');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\User', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAchievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserAchievement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function venue()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Venue');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function achievement()
    {
        return $this->hasOne('Zeropingheroes\Lanager\Achievement', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slides()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Slide');
    }
}
