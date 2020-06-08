<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
/* @mixin Eloquent */

class Venue extends Model
{
    protected $fillable = [
        'name',
        'street_address',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lans()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Lan');
    }
}
