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
 * @property string $title
 * @property string|null $content
 * @property int $published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lan $lan
 *
 * @method static Builder<static>|Guide newModelQuery()
 * @method static Builder<static>|Guide newQuery()
 * @method static Builder<static>|Guide query()
 * @method static Builder<static>|Guide whereContent($value)
 * @method static Builder<static>|Guide whereCreatedAt($value)
 * @method static Builder<static>|Guide whereId($value)
 * @method static Builder<static>|Guide whereLanId($value)
 * @method static Builder<static>|Guide wherePublished($value)
 * @method static Builder<static>|Guide whereTitle($value)
 * @method static Builder<static>|Guide whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Guide extends Model
{
    protected $fillable = [
        'lan_id',
        'title',
        'content',
        'published',
    ];

    protected $with = [
        'lan',
    ];

    /**
     * LAN the guide is a part of
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo(Lan::class);
    }
}
