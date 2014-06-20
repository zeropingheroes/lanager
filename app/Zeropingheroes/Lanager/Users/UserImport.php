<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\Interfaces\SteamUserRepositoryInterface;
use Zeropingheroes\Lanager\Models\User;
use Request, Event;

class UserImport {
	
	protected $steamUserInterface;
	
	public function __construct(SteamUserRepositoryInterface $steamUserInterface)
	{
		$this->steamUserInterface = $steamUserInterface;
	}

	public function fromSteam($steamId64)
	{
		if( $steamUser = $this->steamUserInterface->getUser($steamId64) )
		{
			$user = User::where('steam_id_64', '=', $steamId64)->first(); // do not constrain to visible users

			// Create new user if they are not found in the database
			if($user == NULL) $user = new User;

			$user->username 		= $steamUser->username;
			$user->steam_id_64		= $steamUser->id;
			$user->steam_visibility	= $steamUser->visibility;
			$user->visible			= true;	// make an invisible user visible again if they are returning to the lan
			$user->avatar			= $steamUser->avatar_url;
			$user->ip 				= Request::server('REMOTE_ADDR');

			if( $user->save() )
			{
				Event::fire('lanager.users.store', $user );
				return $user;
			}
		}
		else
		{
			return false;
		}

	}

}