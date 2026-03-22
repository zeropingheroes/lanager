<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class Session extends Model
{
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    protected $keyType = 'string';

    /**
     * User whose session it is
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
