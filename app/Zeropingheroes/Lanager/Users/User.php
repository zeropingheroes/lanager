<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\Roles\Role;
use Illuminate\Auth\UserInterface,
	Illuminate\Support\MessageBag;

class User extends BaseModel implements UserInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public static $rules = array(
		'username'			=> 'required|max:32',
		'steam_id_64'		=> 'required|max:17',
		'steam_visibility'	=> 'required',
		'ip'				=> 'ip',
		'avatar'			=> 'url',
		//'visible'			=> 'boolean'
	);

	public function shouts()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Shouts\Shout');
	}

	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

	public function votes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Votes\Vote');
	}

	public function events()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Events\Event', 'event_signups')->withTimestamps();
	}

	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Awards\Award');
	}

	public function achievements()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Achievements\Achievement', 'awards');
	}

	public function roles()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Roles\Role');
	}

	public function roleAssignments()
	{
		return $this->hasMany('Zeropingheroes\Lanager\RoleAssignments\RoleAssignment');
	}

	public function permissions()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Permissions\Permission');
	}

	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

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

	public function hasRole($key) 
	{
		foreach($this->roleAssignments as $roleAssignment)
		{
			if($roleAssignment->role->name === $key)
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

}