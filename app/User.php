<?php

namespace Zeropingheroes\Lanager;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The relationships that should always be eager loaded
     *
     * @var array
     */
    protected $with = [
        'roles',
        'SteamMetadata',
        'SteamMetadata.status',
    ];

    /**
     * Get the user's linked accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OAuthAccounts()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserOAuthAccount');
    }

    /**
     * Get the user's Steam apps
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function SteamApps()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserApp');
    }

    /**
     * Get the user's Steam visibility
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function SteamMetadata()
    {
        return $this->hasOne('Zeropingheroes\Lanager\SteamUserMetadata')
            ->withDefault();
    }

    /**
     * The roles that belong to the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this
            ->belongsToMany('Zeropingheroes\Lanager\Role', 'role_assignments')
            ->withTimestamps();
    }

    /**
     * Check if the user has the specified role(s)
     *
     * @param string $role
     * @return bool
     * @internal param $role
     */
    public function hasRole(string $role)
    {
        return in_array($role, $this->roles->pluck('name')->toArray());
    }

    /**
     * The LANs the user has attended
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lans()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Lan', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * Get the user's sessions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steamAppSessions()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserAppSession');
    }

    public function eventSignups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\EventSignup');
    }

}
