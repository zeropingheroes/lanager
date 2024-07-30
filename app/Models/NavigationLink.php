<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zeropingheroes\Lanager\Observers\NavigationLinkObserver;

/* @mixin Eloquent */
#[ObservedBy([NavigationLinkObserver::class])]
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
        return $this->belongsTo('Zeropingheroes\Lanager\Models\NavigationLink', 'parent_id')
            ->withDefault();
    }

    /**
     * Child navigation links
     */
    public function children(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\NavigationLink', 'parent_id');
    }
}
