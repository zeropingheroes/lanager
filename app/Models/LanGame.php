<?php

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\LanGameFactory;
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
 * @property string $game_name
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lan $lan
 * @property-read User $user
 * @property-read Collection<int, LanGameVote> $votes
 * @property-read int|null $votes_count
 *
 * @method static LanGameFactory factory($count = null, $state = [])
 * @method static Builder<static>|LanGame newModelQuery()
 * @method static Builder<static>|LanGame newQuery()
 * @method static Builder<static>|LanGame query()
 * @method static Builder<static>|LanGame whereCreatedAt($value)
 * @method static Builder<static>|LanGame whereCreatedBy($value)
 * @method static Builder<static>|LanGame whereGameName($value)
 * @method static Builder<static>|LanGame whereId($value)
 * @method static Builder<static>|LanGame whereLanId($value)
 * @method static Builder<static>|LanGame whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class LanGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_id',
        'game_name',
        'created_by',
    ];

    protected $with = [
        'votes.user',
    ];

    /**
     * LAN that the game suggestion belongs to
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }

    /**
     * User who suggested the game
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Votes for the game
     */
    public function votes(): HasMany
    {
        return $this->hasMany(LanGameVote::class);
    }
}
