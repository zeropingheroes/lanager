<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

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
