<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class LanAttendee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lan_id',
        'user_id',
    ];
}
