<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\AttendeeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lan_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static AttendeeFactory factory($count = null, $state = [])
 * @method static Builder<static>|Attendee newModelQuery()
 * @method static Builder<static>|Attendee newQuery()
 * @method static Builder<static>|Attendee query()
 * @method static Builder<static>|Attendee whereCreatedAt($value)
 * @method static Builder<static>|Attendee whereId($value)
 * @method static Builder<static>|Attendee whereLanId($value)
 * @method static Builder<static>|Attendee whereUpdatedAt($value)
 * @method static Builder<static>|Attendee whereUserId($value)
 *
 * @mixin Eloquent
 */
class Attendee extends Pivot
{
    use HasFactory;

    protected $table = 'lan_attendees';

    protected $fillable = [
        'lan_id',
        'user_id',
    ];
}
