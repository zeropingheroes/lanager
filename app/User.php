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
     * Pseudo-relation: A single user's most recent state
     *
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function state()
    {
        $start = Carbon::createFromTimeStamp(time() - (60));
        $end = Carbon::createFromTimeStamp(time() + (60));

        return $this->hasOne('Zeropingheroes\Lanager\SteamUserState')
            ->join(
                DB::raw('(
								SELECT max(created_at) max_created_at, user_id
								FROM steam_user_states
								WHERE created_at
									BETWEEN "'.$start.'"
									AND 	"'.$end.'"
								GROUP BY user_id
								) s2'),
                function ($join) {
                    $join->on('steam_user_states.user_id', '=', 's2.user_id')
                        ->on('steam_user_states.created_at', '=', 's2.max_created_at');
                })
            ->orderBy('steam_user_states.user_id');
    }

    /**
     * The LANs the user has attended
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lans()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Lan', 'lan_attendees')
            ->using('Zeropingheroes\Lanager\LanAttendee')
            ->as('attendance')
            ->withTimestamps();
    }

}
