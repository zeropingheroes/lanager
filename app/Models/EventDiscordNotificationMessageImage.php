<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\EventDiscordNotificationMessageImageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $event_discord_notification_message_id
 * @property string $image_path
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property-read EventDiscordNotificationMessage $notificationMessage
 *
 * @method static EventDiscordNotificationMessageImageFactory factory(...$parameters)
 * @method static Builder<static> query()
 */
class EventDiscordNotificationMessageImage extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $table = 'event_discord_notification_message_images';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'event_discord_notification_message_id',
        'image_path',
        'sort_order',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function notificationMessage(): BelongsTo
    {
        return $this->belongsTo(EventDiscordNotificationMessage::class, 'event_discord_notification_message_id');
    }
}
