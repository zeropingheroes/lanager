<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
/* @mixin Eloquent */

class WhitelistedIpRange extends Model
{
    protected $fillable = [
        'ip_range',
        'description',
    ];
}
