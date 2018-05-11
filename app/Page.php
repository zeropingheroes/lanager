<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
     * Scope a query to only show pages visible to the user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        if (Auth::user() && Auth::user()->can('update', new Page)) {
            return $query;
        } else {
            return $query->where('published', '=', 1);
        }
    }
}
