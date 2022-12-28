<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

/* @mixin Eloquent */
class Session extends Model
{
    protected $dates = [
        'last_activity',
    ];

    protected $keyType = 'string';

    /**
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }
}
