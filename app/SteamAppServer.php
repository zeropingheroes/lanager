<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class SteamAppServer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'steam_app_id',
        'address',
        'port',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\SteamApp');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserState');
    }

    /**
     * URL to connect to the server using Steam Browser Protocol
     *
     * @return string
     */
    public function url()
    {
        if ($this->address && $this->port) {
            return 'steam://connect/' . $this->address . ':' . $this->port;
        }
        return 'steam://connect/' . $this->address;
    }
}
