<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * Get the user who caused the log entry
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User', 'created_by');
    }
}
