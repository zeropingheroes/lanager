<?php

namespace Zeropingheroes\Lanager;

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
