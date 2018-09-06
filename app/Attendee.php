<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendee extends Pivot
{
    protected $table = 'lan_attendees';

    protected $fillable = [
        'lan_id',
        'user_id',
    ];
}
