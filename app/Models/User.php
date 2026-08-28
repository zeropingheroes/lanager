<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Zeropingheroes\Lanager\Observers\UserObserver;

#[ObservedBy([UserObserver::class])]
/**
 * @property int $id
 * @property string $username
 * @property string|null $api_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, UserOAuthAccount> $accounts
 * @property-read int|null $accounts_count
 * @property-read Collection<int, UserAchievement> $achievements
 * @property-read int|null $achievements_count
 * @property-read Collection<int, EventSignup> $eventSignups
 * @property-read int|null $event_signups_count
 * @property-read Collection<int, LanGameVote> $lanGameVotes
 * @property-read int|null $lan_game_votes_count
 * @property-read Collection<int, LanGame> $lanGames
 * @property-read int|null $lan_games_count
 * @property-read Attendee|null $attendance
 * @property-read Collection<int, Lan> $lans
 * @property-read int|null $lans_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read RoleAssignment|null $pivot
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Session> $sessions
 * @property-read int|null $sessions_count
 * @property-read Collection<int, SteamUserAppSession> $steamAppSessions
 * @property-read int|null $steam_app_sessions_count
 * @property-read Collection<int, SteamUserApp> $steamApps
 * @property-read int|null $steam_apps_count
 * @property-read SteamUserMetadata $steamMetadata
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereApiToken($value)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @method static Builder<static>|User whereUsername($value)
 *
 * @mixin Eloquent
 */
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
     * Roles assigned to the user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_assignments')
            ->using(RoleAssignment::class);
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
        return $this->hasMany(UserOAuthAccount::class);
    }

    /**
     * LANs the user has attended
     */
    public function lans(): BelongsToMany
    {
        return $this->belongsToMany(Lan::class, 'lan_attendees')
            ->using(Attendee::class)
            ->as('attendance')
            ->withTimestamps();
    }

    /**
     * Event signups belonging to the user
     */
    public function eventSignups(): HasMany
    {
        return $this->hasMany(EventSignup::class);
    }

    /**
     * LAN games submitted by the user
     */
    public function lanGames(): HasMany
    {
        return $this->hasMany(LanGame::class);
    }

    /**
     * LAN game votes cast by the user
     */
    public function lanGameVotes(): HasMany
    {
        return $this->hasMany(LanGameVote::class);
    }

    /**
     * Achievements awarded to the user
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Steam apps in the user's Steam library
     */
    public function steamApps(): HasMany
    {
        return $this->hasMany(SteamUserApp::class);
    }

    /**
     * User's Steam account metadata record
     */
    public function steamMetadata(): HasOne
    {
        return $this->hasOne(SteamUserMetadata::class)
            ->withDefault();
    }

    /**
     * User's Steam app sessions (gameplay sessions)
     */
    public function steamAppSessions(): HasMany
    {
        return $this->hasMany(SteamUserAppSession::class);
    }

    /**
     * User's web browser sessions
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
