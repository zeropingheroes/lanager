<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class WhitelistedIpRange extends Model
{
    protected $fillable = [
        'ip_range',
        'description',
    ];
}
