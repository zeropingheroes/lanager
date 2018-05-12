<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class NavigationLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'position',
        'url',
        'parent_id',
    ];

    /**
     * A navigation link may optionally have one parent
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function parent()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\NavigationLink', 'parent_id')
            ->withDefault();
    }

    /**
     * A navigation link may optionally have many children
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function children()
    {
        return $this->hasMany('Zeropingheroes\Lanager\NavigationLink', 'parent_id');
    }
}
