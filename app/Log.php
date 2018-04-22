<?php

namespace Zeropingheroes\Lanager;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use Filterable;

    /**
     * Get the user who caused the log entry
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'created_by');
    }
}
