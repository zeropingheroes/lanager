<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */
class Guide extends Model
{
    protected $fillable = [
        'lan_id',
        'title',
        'content',
        'published',
    ];

    protected $with = [
        'lan',
    ];

    /**
     * LAN the guide is a part of
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }
}
