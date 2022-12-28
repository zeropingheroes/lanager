<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/* @mixin Eloquent */
class AllowedIpRange extends Model
{
    protected $fillable = [
        'ip_range',
        'description',
    ];
}
