<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\Roles\Role;
use Log;

class UserHandler {

	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return array
	*/
	public function subscribe($events)
	{
		$events->listen('lanager.users.store', 'Zeropingheroes\Lanager\Users\UserHandler@onStore');
	}

	/**
	 * Perform actions after new user has been stored
	 * @param  BaseModel $user User that has just been stored
	 */
	public function onStore($user)
	{
		// Make the first user SuperAdmin
		if( count(User::all()) == 1 && ! $user->hasRole('Super Admin') )
		{
			$user->roles()->attach(Role::where('name', '=', 'Super Admin')->firstOrFail());
			Log::debug( e($user->username) . ': Assigning "Super Admin" role as first user to log in', [$user->api_key]);
		}

		// Generate an API key if the user does not have one
		if( empty($user->api_key) )
		{
			$user->api_key = md5(str_random(32));
			$user->save();
			Log::debug( e($user->username) . ': Generating API key', [$user->api_key]);
		}
	}

}
