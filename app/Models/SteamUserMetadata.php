<?php

namespace Zeropingheroes\Lanager\Models;

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
     * User who the metadata record belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
    }

    /**
     * Status code of the user
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Models\SteamUserStatusCode', 'steam_user_status_code_id');
    }
}
