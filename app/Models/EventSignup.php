<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Event $event
 * @property-read User $user
 *
 * @method static Builder<static>|EventSignup newModelQuery()
 * @method static Builder<static>|EventSignup newQuery()
 * @method static Builder<static>|EventSignup query()
 * @method static Builder<static>|EventSignup whereCreatedAt($value)
 * @method static Builder<static>|EventSignup whereEventId($value)
 * @method static Builder<static>|EventSignup whereId($value)
 * @method static Builder<static>|EventSignup whereUpdatedAt($value)
 * @method static Builder<static>|EventSignup whereUserId($value)
 *
 * @mixin Eloquent
 */
class EventSignup extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
    ];

    /**
     * Event the signups are for
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * User who signed up to the event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
