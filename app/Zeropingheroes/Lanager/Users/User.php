<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseModel;
use Illuminate\Auth\UserInterface;
use Laracasts\Presenter\PresentableTrait;
use DB;

class User extends BaseModel implements UserInterface {

	protected $fillable = ['username', 'steam_id_64', 'steam_visibility', 'ip', 'avatar', 'visible'];

	protected $hidden = ['remember_token', 'api_key'];

	public $validator = 'Zeropingheroes\Lanager\Users\UserValidator';

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Users\UserPresenter';

	public function shouts()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Shouts\Shout');
	}

	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

	public function playlistItemVotes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVote');
	}

	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

	public function roles()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Roles\Role', 'user_roles');
	}

	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\EventSignups\EventSignup', 'event_signups');
	}

	public function scopeVisible($query)
	{
		return $query->whereVisible(true);
	}

	// all states belonging to a user
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	// psuedo-relation for a user's most recent state
	public function state()
	{
		return $this->hasOne('Zeropingheroes\Lanager\States\State')
					->join(
						DB::raw('(
								SELECT max(created_at) max_created_at, user_id
								FROM states
								WHERE created_at
									BETWEEN from_unixtime('.(time()-60).') AND from_unixtime('.time().')
								GROUP BY user_id
								) s2'),
						function($join)
						{
							$join->on('states.user_id', '=', 's2.user_id')
								 ->on('states.created_at', '=', 's2.max_created_at');
						})
					->orderBy('states.user_id');
	}

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