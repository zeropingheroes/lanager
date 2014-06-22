<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\Roles\Role;

class UserHandler {

	public function onStore($user)
	{
		// Make the first user SuperAdmin
		if( count(User::all()) == 1 && ! $user->hasRole('SuperAdmin') )	$user->roles()->attach(Role::where('name', '=', 'SuperAdmin')->firstOrFail());
	}

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

}
