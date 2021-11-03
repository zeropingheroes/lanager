<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/* @mixin Eloquent */
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
     * @param  Builder $query
     * @return Builder
     */
    public function scopeHappeningNow($query)
    {
        return $query->where('start', '<', now())
            ->where('end', '>', now());
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopePast($query)
    {
        return $query->where('end', '<', now());
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFuture($query)
    {
        return $query->where('start', '>', now());
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopePresentAndPast($query)
    {
        return $query->where('start', '<', now());
    }

    /**
     * @return HasMany
     */
    public function events()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Event');
    }

    /**
     * @return HasMany
     */
    public function guides()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Guide');
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\User', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function userAchievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserAchievement');
    }

    /**
     * @return belongsTo
     */
    public function venue()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Venue');
    }

    /**
     * @return HasOne
     */
    public function attendanceAchievement()
    {
        return $this->hasOne('Zeropingheroes\Lanager\Achievement', 'id', 'achievement_id');
    }

    /**
     * @return HasMany
     */
    public function slides()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Slide');
    }

    /**
     * @return HasMany
     */
    public function games()
    {
        return $this->hasMany('Zeropingheroes\Lanager\LanGame');
    }
}
