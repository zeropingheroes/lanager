<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\DiscordChannelWebhookFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lan_id
 * @property string $purpose
 * @property string $webhook_url
 * @property Carbon|null $created_at
 * @property-read Lan $lan
 *
 * @method static DiscordChannelWebhookFactory factory(...$parameters)
 * @method static Builder<static> query()
 */
class DiscordChannelWebhook extends Model
{
    use HasFactory;

    protected $table = 'discord_channel_webhooks';

    /**
     * No updated_at column. Webhook URLs are immutable after creation.
     */
    public const UPDATED_AT = null;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'lan_id',
        'purpose',
        'webhook_url',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The LAN this webhook belongs to.
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }
}
