<?php namespace Zeropingheroes\Lanager\Models;

use Illuminate\Auth\UserInterface;

class User extends BaseModel implements UserInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array(); // Currently OpenID only so no passwords are stored

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function scopeVisible($query)
	{
		return $query->whereVisible(true);
	}

	public function roles()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Models\Role');
	}

	public function permissions()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Permission');
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

	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\State');
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

	public function shouts()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Shout');
	}

	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Item');
	}

	public function votes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Item\Vote');
	}

	public function events()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Models\Event', 'event_signups')->withTimestamps();
	}

	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Award');
	}

	public function achievements()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Models\Achievement', 'awards');
	}

}