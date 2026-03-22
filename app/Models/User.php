<?php

namespace Zeropingheroes\Lanager\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zeropingheroes\Lanager\Observers\UserObserver;

/* @mixin Eloquent */
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory;
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

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->with['steamAppSessions'] = function ($query) {
            $query->active();
        };
    }

    /**
     * Roles assigned to the user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\Role', 'role_assignments')
            ->using('Zeropingheroes\Lanager\Models\RoleAssignment');
    }

    /**
     * Check if the user has a role
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles->pluck('name')->toArray());
    }

    /**
     * OAuth acocunts owned by the user
     */
    public function accounts(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserOAuthAccount');
    }

    /**
     * LANs the user has attended
     */
    public function lans(): BelongsToMany
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Models\Lan', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\Models\Attendee')
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * Event signups belonging to the user
     */
    public function eventSignups(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\EventSignup');
    }

    /**
     * LAN games submitted by the user
     */
    public function lanGames(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\LanGame');
    }

    /**
     * LAN game votes cast by the user
     */
    public function lanGameVotes(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\LanGameVote');
    }

    /**
     * Achievements awarded to the user
     */
    public function achievements(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\UserAchievement');
    }

    /**
     * Steam apps in the user's Steam library
     */
    public function steamApps(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\SteamUserApp');
    }

    /**
     * User's Steam account metadata record
     */
    public function steamMetadata(): HasOne
    {
        return $this->hasOne('Zeropingheroes\Lanager\Models\SteamUserMetadata')
            ->withDefault();
    }

    /**
     * User's Steam app sessions (gameplay sessions)
     */
    public function steamAppSessions(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\SteamUserAppSession');
    }

    /**
     * User's web browser sessions
     */
    public function sessions(): HasMany
    {
        return $this->hasMany('Zeropingheroes\Lanager\Models\Session');
    }
}
