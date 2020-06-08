<?php

namespace Zeropingheroes\Lanager;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @mixin Eloquent */

class SteamUserMetadata extends Model
{
    protected $fillable = [
        'user_id',
        'steam_user_status_code_id',
        'profile_visible',
        'apps_visible',
        'profile_updated_at',
        'apps_updated_at',
    ];

    protected $table = 'steam_user_metadata';

    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamUserStatusCode', 'steam_user_status_code_id');
    }
}
