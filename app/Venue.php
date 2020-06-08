<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */

class Venue extends Model
{
    protected $fillable = [
        'name',
        'street_address',
        'description',
    ];

    /**
     * @return HasMany
     */
    public function lans()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Lan');
    }
}
