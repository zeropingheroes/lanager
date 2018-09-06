<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamUserStatusCode extends Model
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
