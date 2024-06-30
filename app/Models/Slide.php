<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

/* @mixin Eloquent */
class Slide extends Model
{
    protected $fillable = [
        'lan_id',
        'name',
        'content',
        'position',
        'duration',
        'start',
        'end',
        'published',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }
}
