<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

/* @mixin Eloquent */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'lan_id',
        'name',
        'description',
        'published',
        'start',
        'end',
        'signups_open',
        'signups_close',
    ];

    protected $dates = [
        'start',
        'end',
        'signups_open',
        'signups_close',
    ];

    /**
     * @return belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }

    /**
     * @return hasMany
     */
    public function signups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\EventSignup');
    }
}
