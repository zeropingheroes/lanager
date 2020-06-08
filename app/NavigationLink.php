<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
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
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\NavigationLink', 'parent_id')
            ->withDefault();
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('Zeropingheroes\Lanager\NavigationLink', 'parent_id');
    }
}
