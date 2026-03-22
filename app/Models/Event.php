<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'signups_open' => 'datetime',
        'signups_close' => 'datetime',
    ];

    /**
     * LAN the event is a part of
     */
    public function lan(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
    }

    /**
     * Event's signups
     */
    public function signups(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\EventSignup');
    }
}
