<?php

namespace Zeropingheroes\Lanager;

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

    protected $dates = [
        'start',
        'end',
    ];

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }
}
