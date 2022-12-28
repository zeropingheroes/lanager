<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/* @mixin Eloquent */
class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

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
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\Role', 'role_assignments')
            ->using('Zeropingheroes\Lanager\Models\RoleAssignment');
    }

    /**
     * Check if the user has the specified role(s).
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return in_array($role, $this->roles->pluck('name')->toArray());
    }

    /**
     * @return HasMany
     */
    public function accounts()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserOAuthAccount');
    }

    /**
     * @return BelongsToMany
     */
    public function lans()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\Lan', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Models\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function eventSignups()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\EventSignup');
    }

    /**
     * @return HasMany
     */
    public function lanGames()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\LanGame');
    }

    /**
     * @return HasMany
     */
    public function lanGameVotes()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\LanGameVote');
    }

    /**
     * @return HasMany
     */
    public function achievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserAchievement');
    }

    /**
     * @return HasMany
     */
    public function steamApps()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\SteamUserApp');
    }

    /**
     * @return HasOne
     */
    public function steamMetadata()
    {
        return $this->hasOne('Zeropingheroes\Lanager\Models\SteamUserMetadata')
            ->withDefault();
    }

    /**
     * @return HasMany
     */
    public function steamAppSessions()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\SteamUserAppSession');
    }

    /**
     * @return HasMany
     */
    public function sessions()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\Session');
    }
}
