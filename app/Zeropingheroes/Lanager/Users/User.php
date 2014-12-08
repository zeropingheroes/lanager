<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseModel;
use Illuminate\Auth\UserInterface;

class User extends BaseModel implements UserInterface {

	protected $fillable = ['username', 'steam_id_64', 'steam_visibility', 'ip', 'avatar', 'visible'];

	public $validator = 'Zeropingheroes\Lanager\Users\UserValidator';

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
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistsItemVotes\PlaylistItemVote');
	}

	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

    public function roles()
    {
        return $this->belongsToMany('Zeropingheroes\Lanager\Roles\Role', 'user_roles');
    }

	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\EventSignups\EventSignup', 'event_signups');
	}

	public function scopeVisible($query)
	{
		return $query->whereVisible(true);
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

	/**
	 * Get the URL for the user's medium avatar.
	 *
	 * @return string
	 */
	public function getMediumAvatarUrl()
	{
		return str_replace('.jpg', '_medium.jpg', $this->avatar);
	}

	/**
	 * Get the URL for the user's large avatar.
	 *
	 * @return string
	 */
	public function getLargeAvatarUrl()
	{
		return str_replace('.jpg', '_full.jpg', $this->avatar);
	}


	/*
	|--------------------------------------------------------------------------------
	| Redundant code below due to implementing Laravel's UserInterface contract...
	|--------------------------------------------------------------------------------
	*/

	protected $hidden = array('remember_token');

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