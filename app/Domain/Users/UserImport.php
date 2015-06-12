<?php namespace Zeropingheroes\Lanager\Domain\Users;

use	Zeropingheroes\Lanager\Domain\Users\SteamUsers\SteamUserContract;
use Request;
use Event;

class UserImport {
	
	protected $steamUser;
	
	public function __construct( SteamUserContract $steamUser )
	{
		$this->steamUser = $steamUser;
	}

	public function fromSteam( $steamId64 )
	{
		$steamUser = $this->steamUser->getUser( $steamId64 );

		// Create new user if they are not found in the database
		if ( ! $user = User::where( 'steam_id_64', '=', $steamId64 )->first() ) $user = new User;

		$user->username 		= $steamUser->username;
		$user->steam_id_64		= $steamUser->id;
		$user->steam_visibility	= $steamUser->visibility;
		$user->visible			= true;	// imported users visible by default
		$user->avatar			= $steamUser->avatar_url;
		$user->ip 				= Request::server( 'REMOTE_ADDR' );

		if ( $user->save() )
		{
			Event::fire( 'lanager.users.store', $user ); // todo: use service
			return $user;
		}

		throw new UserImportException( $user->errors() ); // Cast to string as JSON
	}

}