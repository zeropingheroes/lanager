<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\LanFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $venue_id
 * @property int|null $achievement_id
 * @property string $name
 * @property Carbon $start
 * @property Carbon $end
 * @property int $published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Achievement|null $attendanceAchievement
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, DiscordChannelWebhook> $discordChannelWebhooks
 * @property-read int|null $discord_channel_webhooks_count
 * @property-read Collection<int, LanGame> $games
 * @property-read int|null $games_count
 * @property-read Collection<int, Guide> $guides
 * @property-read int|null $guides_count
 * @property-read Collection<int, Slide> $slides
 * @property-read int|null $slides_count
 * @property-read Collection<int, UserAchievement> $userAchievements
 * @property-read int|null $user_achievements_count
 * @property-read Attendee|null $attendance
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read Venue|null $venue
 *
 * @method static LanFactory factory($count = null, $state = [])
 * @method static Builder<static>|Lan future()
 * @method static Builder<static>|Lan happeningNow()
 * @method static Builder<static>|Lan newModelQuery()
 * @method static Builder<static>|Lan newQuery()
 * @method static Builder<static>|Lan past()
 * @method static Builder<static>|Lan presentAndPast()
 * @method static Builder<static>|Lan query()
 * @method static Builder<static>|Lan whereAchievementId($value)
 * @method static Builder<static>|Lan whereCreatedAt($value)
 * @method static Builder<static>|Lan whereEnd($value)
 * @method static Builder<static>|Lan whereId($value)
 * @method static Builder<static>|Lan whereName($value)
 * @method static Builder<static>|Lan wherePublished($value)
 * @method static Builder<static>|Lan whereStart($value)
 * @method static Builder<static>|Lan whereUpdatedAt($value)
 * @method static Builder<static>|Lan whereVenueId($value)
 *
 * @mixin Eloquent
 */
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
    public function scopeHappeningNow(Builder $builder): Builder
    {
        return $builder->where('start', '<', now())
            ->where('end', '>', now());
    }

    /**
     * LANs that have ended
     */
    public function scopePast(Builder $builder): Builder
    {
        return $builder->where('end', '<', now());
    }

    /**
     * LANs that have not yet started
     */
    public function scopeFuture(Builder $builder): Builder
    {
        return $builder->where('start', '>', now());
    }

    /**
     * LANs that have ended or have not started
     */
    public function scopePresentAndPast(Builder $builder): Builder
    {
        return $builder->where('start', '<', now());
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

    /**
     * Discord channel webhooks configured for this LAN
     */
    public function discordChannelWebhooks(): HasMany
    {
        return $this->hasMany(DiscordChannelWebhook::class);
    }
}
