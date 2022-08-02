<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/* @mixin Eloquent */
class Attendee extends Pivot
{
    use HasFactory;

    protected $table = 'lan_attendees';

    protected $fillable = [
        'lan_id',
        'user_id',
    ];
}
