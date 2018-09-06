<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $fillable = [
        'name',
        'colour',
    ];
}
