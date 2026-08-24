<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\EventDiscordNotificationMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $event_id
 * @property string|null $message
 * @property bool $automatic
 * @property Carbon|null $automatically_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Event $event
 * @property-read Collection<int, EventDiscordNotificationMessageImage> $images
 *
 * @method static EventDiscordNotificationMessageFactory factory(...$parameters)
 * @method static Builder<static> query()
 */
class EventDiscordNotificationMessage extends Model
{
    use HasFactory;

    protected $table = 'event_discord_notification_messages';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'message',
        'automatic',
        'automatically_sent_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'automatic' => 'boolean',
        'automatically_sent_at' => 'datetime',
    ];

    /**
     * The message text to send, falling back to the default message when none is set.
     */
    public function content(): string
    {
        return $this->message ?? trans('phrase.default-event-discord-notification-message');
    }

    /**
     * The event this notification message belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Images attached to this notification message, ordered by sort_order then id.
     *
     * @return HasMany<EventDiscordNotificationMessageImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(EventDiscordNotificationMessageImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
