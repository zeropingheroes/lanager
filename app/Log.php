<?php

namespace Zeropingheroes\Lanager;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use Filterable;

    protected $fillable = [
        'read',
    ];

    /**
     * The user who triggered the log entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'created_by')
            ->withDefault();
    }
}
