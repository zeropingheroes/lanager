<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Zeropingheroes\Lanager\Observers\NavigationLinkObserver;

#[ObservedBy([NavigationLinkObserver::class])]
/**
 * @property int $id
 * @property string $title
 * @property int $position
 * @property string|null $url
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, NavigationLink> $children
 * @property-read int|null $children_count
 * @property-read NavigationLink|null $parent
 *
 * @method static Builder<static>|NavigationLink newModelQuery()
 * @method static Builder<static>|NavigationLink newQuery()
 * @method static Builder<static>|NavigationLink query()
 * @method static Builder<static>|NavigationLink whereCreatedAt($value)
 * @method static Builder<static>|NavigationLink whereId($value)
 * @method static Builder<static>|NavigationLink whereParentId($value)
 * @method static Builder<static>|NavigationLink wherePosition($value)
 * @method static Builder<static>|NavigationLink whereTitle($value)
 * @method static Builder<static>|NavigationLink whereUpdatedAt($value)
 * @method static Builder<static>|NavigationLink whereUrl($value)
 *
 * @mixin Eloquent
 */
class NavigationLink extends Model
{
    protected $fillable = [
        'title',
        'position',
        'url',
        'parent_id',
    ];

    protected $with = [
        'children',
    ];

    /**
     * Parent navigation link
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavigationLink::class, 'parent_id')
            ->withDefault();
    }

    /**
     * Child navigation links
     */
    public function children(): HasMany
    {
        return $this->hasMany(NavigationLink::class, 'parent_id');
    }
}
