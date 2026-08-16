<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\EventDiscordNotificationMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $event_id
 * @property string $message
 * @property bool $automatic
 * @property Carbon|null $automatically_sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Event $event
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
     * The event this notification message belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
