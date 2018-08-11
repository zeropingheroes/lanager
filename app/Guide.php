<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Guide extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lan_id',
        'title',
        'content',
        'published'
    ];

    /**
     * Scope a query to only show guides visible to the user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        if (Auth::user() && Auth::user()->can('update', new Guide)) {
            return $query;
        } else {
            return $query->where('published', '=', 1);
        }
    }

    /**
     * The LAN
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lan()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Lan');
    }
}
