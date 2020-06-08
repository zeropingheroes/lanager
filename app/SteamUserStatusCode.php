<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
/* @mixin Eloquent */

class SteamUserStatusCode extends Model
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
