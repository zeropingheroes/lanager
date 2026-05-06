<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\EventFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lan_id
 * @property string $name
 * @property string|null $description
 * @property int $published
 * @property bool $discord_notify
 * @property string|null $discord_message
 * @property Carbon|null $discord_notified_at
 * @property Carbon $start
 * @property Carbon $end
 * @property Carbon|null $signups_open
 * @property Carbon|null $signups_close
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lan $lan
 * @property-read Collection<int, EventSignup> $signups
 * @property-read int|null $signups_count
 *
 * @method static EventFactory factory($count = null, $state = [])
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event whereCreatedAt($value)
 * @method static Builder<static>|Event whereDescription($value)
 * @method static Builder<static>|Event whereEnd($value)
 * @method static Builder<static>|Event whereId($value)
 * @method static Builder<static>|Event whereLanId($value)
 * @method static Builder<static>|Event whereName($value)
 * @method static Builder<static>|Event wherePublished($value)
 * @method static Builder<static>|Event whereSignupsClose($value)
 * @method static Builder<static>|Event whereSignupsOpen($value)
 * @method static Builder<static>|Event whereStart($value)
 * @method static Builder<static>|Event whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_id',
        'name',
        'description',
        'published',
        'discord_notify',
        'discord_message',
        'discord_notified_at',
        'start',
        'end',
        'signups_open',
        'signups_close',
    ];

    protected $casts = [
        'discord_notify' => 'boolean',
        'discord_notified_at' => 'datetime',
        'start' => 'datetime',
        'end' => 'datetime',
        'signups_open' => 'datetime',
        'signups_close' => 'datetime',
    ];

    /**
     * LAN the event is a part of
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }

    /**
     * Event's signups
     */
    public function signups(): HasMany
    {
        return $this->hasMany(EventSignup::class);
    }
}
