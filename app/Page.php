<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'published'
    ];

    /**
     * A single page may optionally have a single parent
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function parent()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Page', 'parent_id')
            ->withDefault();
    }

    /**
     * Pseudo-relation: A single page may optionally have many children
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function children()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Page', 'parent_id');
    }

    /**
     * Scope a query to only include published pages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', '=', 1);
    }
}
