<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Illuminate\Auth\UserInterface;
use Laracasts\Presenter\PresentableTrait;
use DB;
use Config;
use Carbon\Carbon;

class User extends BaseModel implements UserInterface {

	use PresentableTrait;

	protected $fillable = ['username', 'steam_id_64', 'steam_visibility', 'ip', 'avatar', 'visible'];

	protected $optional = ['steam_visibility', 'visible'];

	protected $hidden = ['remember_token', 'api_key'];

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Domain\Users\UserPresenter';

	/**
	 * A single user has many shouts
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function shouts()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\Shouts\Shout');
	}

	/**
	 * A single user has many playlist items
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItem');
	}

	/**
	 * A single user has many playlist item (skip) votes
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItemVotes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVote');
	}

	/**
	 * A single user has many user achievements (aka awards)
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievement');
	}

	/**
	 * A single user has many roles
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function roles()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Domain\Roles\Role', 'user_roles');
	}

	/**
	 * A single user has many event signups
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\EventSignups\EventSignup');
	}

	/**
	 * A single user has many states
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\States\State');
	}

	/**
	 * Pseudo-relation: A single user's most recent state
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function state()
	{
		$start = Carbon::createFromTimeStamp(time()-(Config::get('lanager/steam.pollingInterval')));
		$end = Carbon::createFromTimeStamp(time()+(Config::get('lanager/steam.pollingInterval')));

		return $this->hasOne('Zeropingheroes\Lanager\Domain\States\State')
					->join(
						DB::raw('(
								SELECT max(created_at) max_created_at, user_id
								FROM states
								WHERE created_at
									BETWEEN "'.$start.'"
									AND 	"'.$end.'"
								GROUP BY user_id
								) s2'),
						function($join)
						{
							$join->on('states.user_id', '=', 's2.user_id')
								 ->on('states.created_at', '=', 's2.max_created_at');
						})
					->orderBy('states.user_id');
	}

	/**
	 * Check if the user has the specified role assigned to them
	 * @param  string  $requiredRoleName Role name
	 * @return boolean      true if user has role, false otherwise
	 */
	public function hasRole($requiredRoleName) 
	{
		foreach($this->roles as $assignedRole)
		{
			// If the user is assigned the "admin" role, let them do everything
			// except things that require superadmin acccess
			if( $assignedRole->name == 'Admin' AND $requiredRoleName != 'Super Admin' )
				return true;

			// If the user is assigned the "super admin" role, let them do everything
			if( $assignedRole->name == 'Super Admin' )
				return true;

			// Otherwise just check if they have the role
			return ($assignedRole->name === $requiredRoleName);
		}
		return false;
	}

	/**
	 * Check if the user is an administrator of any kind
	 * @return boolean		true if user is an admin, false otherwise
	 */
	public function isAdmin()
	{
		foreach($this->roles as $role)
		{
			if(str_contains(strtolower($role->name), 'admin'))
			{
				return true;
			}
		}
		return false;
	}

	/*
	|--------------------------------------------------------------------------------
	| Redundant code below due to implementing Laravel's UserInterface contract...
	|--------------------------------------------------------------------------------
	*/

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

}