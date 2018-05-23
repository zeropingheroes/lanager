<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamUserMetadata extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'profile_visible',
        'apps_visible',
        'profile_updated_at',
        'apps_updated_at',
    ];

    /**
     * The table for this model
     *
     * @var string
     */
    protected $table = 'steam_user_metadata';

    /**
     * Whether this model has timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }
}
