<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lan_id
 * @property string $name
 * @property string $content
 * @property int $position
 * @property int $duration
 * @property int $published
 * @property Carbon|null $start
 * @property Carbon|null $end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lan $lan
 *
 * @method static Builder<static>|Slide newModelQuery()
 * @method static Builder<static>|Slide newQuery()
 * @method static Builder<static>|Slide query()
 * @method static Builder<static>|Slide visibleNow()
 * @method static Builder<static>|Slide whereContent($value)
 * @method static Builder<static>|Slide whereCreatedAt($value)
 * @method static Builder<static>|Slide whereDuration($value)
 * @method static Builder<static>|Slide whereEnd($value)
 * @method static Builder<static>|Slide whereId($value)
 * @method static Builder<static>|Slide whereLanId($value)
 * @method static Builder<static>|Slide whereName($value)
 * @method static Builder<static>|Slide wherePosition($value)
 * @method static Builder<static>|Slide wherePublished($value)
 * @method static Builder<static>|Slide whereStart($value)
 * @method static Builder<static>|Slide whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Slide extends Model
{
    protected $fillable = [
        'lan_id',
        'name',
        'content',
        'position',
        'duration',
        'start',
        'end',
        'published',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * LAN the slide is for
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }

    /**
     * Slides visible now
     */
    public function scopeVisibleNow(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where(function (Builder $query) {
                $query->whereNull('start')
                    ->whereNull('end');
            })->orWhere(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->whereNull('start')
                        ->orWhere('start', '<=', now());
                })->where(function (Builder $query) {
                    $query->whereNull('end')
                        ->orWhere('end', '>=', now());
                });
            });
        });
    }
}
