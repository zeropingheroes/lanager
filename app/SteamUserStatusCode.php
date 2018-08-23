<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamUserStatusCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'display_name',
    ];

    /**
     * Whether this model has timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function SteamUserMetadata()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserMetadata');
    }
}
