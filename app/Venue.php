<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'street_address',
        'description',
    ];
}
