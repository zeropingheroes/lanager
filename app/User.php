<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Eloquent;
/* @mixin Eloquent */

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $with = [
        'roles',
        'accounts',
        'steamMetadata',
        'steamMetadata.status',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->with['steamAppSessions'] = function ($query) {
            $query->active();
        };
    }

    /**
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserOAuthAccount');
    }

    /**
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventSignups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\EventSignup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lanGames()
    {
        return $this->hasMany('Zeropingheroes\Lanager\LanGame');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lanGameVotes()
    {
        return $this->hasMany('Zeropingheroes\Lanager\LanGameVote');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserAchievement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steamApps()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserApp');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function steamMetadata()
    {
        return $this->hasOne('Zeropingheroes\Lanager\SteamUserMetadata')
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steamAppSessions()
    {
        return $this->hasMany('Zeropingheroes\Lanager\SteamUserAppSession');
    }
}
