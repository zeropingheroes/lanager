<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\LanGameVoteFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lan_game_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read LanGame $lanGame
 * @property-read User $user
 *
 * @method static LanGameVoteFactory factory($count = null, $state = [])
 * @method static Builder<static>|LanGameVote newModelQuery()
 * @method static Builder<static>|LanGameVote newQuery()
 * @method static Builder<static>|LanGameVote query()
 * @method static Builder<static>|LanGameVote whereCreatedAt($value)
 * @method static Builder<static>|LanGameVote whereId($value)
 * @method static Builder<static>|LanGameVote whereLanGameId($value)
 * @method static Builder<static>|LanGameVote whereUpdatedAt($value)
 * @method static Builder<static>|LanGameVote whereUserId($value)
 *
 * @mixin Eloquent
 */
class LanGameVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_game_id',
        'user_id',
    ];

    /**
     * Game the vote is for
     */
    public function lanGame(): BelongsTo
    {
        return $this->belongsTo(LanGame::class);
    }

    /**
     * User who casted the vote
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
