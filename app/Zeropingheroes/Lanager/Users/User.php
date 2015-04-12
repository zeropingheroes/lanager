<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseModel;
use Illuminate\Auth\UserInterface;
use Laracasts\Presenter\PresentableTrait;
use DB, Config, Carbon\Carbon;

class User extends BaseModel implements UserInterface {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['username', 'steam_id_64', 'steam_visibility', 'ip', 'avatar', 'visible'];

	/**
	 * Fields that have a useful default set in the database
	 * If any of these fields are empty when creating or updating the model should be set to this default
	 * @var array
	 */
	protected $optional = ['steam_visibility', 'visible'];

	/**
	 * Fields that should not be exposed when getting the model's JSON form (nb: transformers in use)
	 * @var array
	 */
	protected $hidden = ['remember_token', 'api_key'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Users\UserValidator';

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Users\UserPresenter';

	/**
	 * A single user has many shouts
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function shouts()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Shouts\Shout');
	}

	/**
	 * A single user has many playlist items
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

	/**
	 * A single user has many playlist item (skip) votes
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItemVotes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVote');
	}

	/**
	 * A single user has many user achievements (aka awards)
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

	/**
	 * A single user has many roles
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function roles()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Roles\Role', 'user_roles');
	}

	/**
	 * A single user has many event signups
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\EventSignups\EventSignup');
	}

	/**
	 * A single user has many states
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	/**
	 * Pseudo-relation: A single user's most recent state
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function state()
	{
		$start = Carbon::createFromTimeStamp(time()-(Config::get('lanager/steam.pollingInterval')));
		$end = Carbon::createFromTimeStamp(time()+(Config::get('lanager/steam.pollingInterval')));

		return $this->hasOne('Zeropingheroes\Lanager\States\State')
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
	 * @param  string  $key Role name
	 * @return boolean      true if user has role, false otherwise
	 */
	public function hasRole($key) 
	{
		foreach($this->roles as $role)
		{
			if($role->name === $key)
			{
				return true;
			}
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