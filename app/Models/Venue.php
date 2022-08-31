<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* @mixin Eloquent */
class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street_address',
    ];

    /**
     * @return HasMany
     */
    public function lans()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\Lan');
    }
}
