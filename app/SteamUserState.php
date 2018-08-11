<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamUserState extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'steam_app_id',
        'steam_app_server_id',
        'steam_user_status_code_id',
    ];

    /**
     * The relationships that should always be eager loaded
     *
     * @var array
     */
    protected $with = [
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamApp', 'steam_app_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamAppServer', 'steam_app_server_id')->withDefault();
    }

    /**
     * Get the state's status text based on the status code
     *
     * @return string
     */
    public function status()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamUserStatusCode', 'steam_user_status_code_id');
    }
}
