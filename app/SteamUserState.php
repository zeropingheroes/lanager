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
        'online_status',
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
        return $this->belongsTo('Zeropingheroes\Lanager\SteamApp', 'steam_app_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamAppServer', 'steam_app_server_id');
    }

    /**
     * Get the state's status text based on the status code
     *
     * @return string
     */
    public function statusText()
    {
        switch ($this->status) {
            case '1':
                if (is_null($this->steam_app_id)) {
                    return 'Online';
                }
                if (!is_null($this->steam_app_id)) {
                    return 'In Game';
                }
            case '2':
                return 'Busy';
            case '3':
                return 'Away';
            case '4':
                return 'Snooze';
            case '5':
                return 'Looking to trade';
            case '6':
                return 'Looking to play';
            case '0':
            default:
                return 'Offline';
        }
    }
}
