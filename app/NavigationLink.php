<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class NavigationLink extends Model
{
    protected $fillable = [
        'title',
        'position',
        'url',
        'parent_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\NavigationLink', 'parent_id')
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children()
    {
        return $this->hasMany('Zeropingheroes\Lanager\NavigationLink', 'parent_id');
    }
}
