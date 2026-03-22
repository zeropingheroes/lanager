<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/* @mixin Eloquent */
class Lan extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'achievement_id',
        'name',
        'start',
        'end',
        'published',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * LANs happening now
     */
    public function scopeHappeningNow(Builder $query): Builder
    {
        return $query->where('start', '<', now())
            ->where('end', '>', now());
    }

    /**
     * LANs that have ended
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('end', '<', now());
    }

    /**
     * LANs that have not yet started
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('start', '>', now());
    }

    /**
     * LANs that have ended or have not started
     */
    public function scopePresentAndPast(Builder $query): Builder
    {
        return $query->where('start', '<', now());
    }

    /**
     * Events belonging to the LAN
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Guides belonging to the LAN
     */
    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class);
    }

    /**
     * LAN attendees
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lan_attendees')
            ->using(Attendee::class)
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * LAN attendee achievements
     */
    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * LAN's venue
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * LAN's attendance achievement
     */
    public function attendanceAchievement(): HasOne
    {
        return $this->hasOne(Achievement::class, 'id', 'achievement_id');
    }

    /**
     * Slides belonging to the LAN
     */
    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    /**
     * Games suggested for the LAN
     */
    public function games(): HasMany
    {
        return $this->hasMany(LanGame::class);
    }
}
