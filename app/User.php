<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'steamMetadata',
        'steamMetadata.status',
    ];

    /**
     * The user's roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Role', 'role_assignments')
            ->using('Zeropingheroes\Lanager\RoleAssignment');
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
     * The user's linked accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserOAuthAccount');
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
     * The user's event sign-ups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventSignups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\EventSignup');
    }

    /**
     * The achievements that the user has been awarded
     */
    public function achievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserAchievement');
    }

    /**
     * The user's Steam apps
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steamApps()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserApp');
    }

    /**
     * The user's Steam account metadata
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function steamMetadata()
    {
        return $this->hasOne('Zeropingheroes\Lanager\SteamUserMetadata')
            ->withDefault();
    }

    /**
     * The user's gameplay sessions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steamAppSessions()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserAppSession');
    }
}
