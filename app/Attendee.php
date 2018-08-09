<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendee extends Pivot
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lan_attendees';

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
