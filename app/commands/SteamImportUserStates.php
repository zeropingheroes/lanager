<?php namespace Zeropingheroes\Lanager\Commands;

use Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\States\State,
	Zeropingheroes\Lanager\Applications\Application,
	Zeropingheroes\Lanager\Servers\Server,
	Zeropingheroes\Lanager\Users\SteamUsers\SteamUserContract;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SteamImportUserStates extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'steam:import-user-states';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieve and store Steam user status information for all registered users.';

	/**
	 * The steam user interface.
	 *
	 * @var SteamUserContract
	 */
	protected $steamUserInterface;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(SteamUserContract $steamUserInterface)
	{
		parent::__construct();
		$this->steamUserInterface = $steamUserInterface;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{

		$users = User::visible()->get()->lists('steam_id_64');

		if(count($users) == 0)
		{
			$this->customError('No users in database');
			return;
		}
		
		$this->customInfo('Requesting current status of '.count($users).' users from Steam');
		$steamUserInterface = $this->steamUserInterface->getUsers($users);

		if( count($steamUserInterface) < count($users) ) $this->customError('Steam responded with '.(count($users)-count($steamUserInterface)).' fewer users than requested');

		$this->customInfo('Importing '.count($steamUserInterface).' user states from Steam into database');

		$successCount = 0;
		$failureCount = 0;
		$appFailureCount = 0;

		foreach($steamUserInterface as $steamUser)
		{
			$currentApplication = NULL;
			$currentServer = NULL;

			try
			{
				// Find the user to which the state belongs to
				$user = User::where('steam_id_64', $steamUser->id)->first();

				// Update user details with steam details if they have changed
				if( $user->username != $steamUser->username ) $user->username = $steamUser->username;
				if( $user->avatar != $steamUser->avatar_url ) $user->avatar = $steamUser->avatar_url;
				if( $user->steam_visibility != $steamUser->visibility ) $user->steam_visibility = $steamUser->visibility;
				$user->save();

				// If the user is currently running an app
				if( is_numeric($steamUser->current_app_id) )
				{
					// Find database application ID
					$currentApplication = Application::where('steam_app_id', $steamUser->current_app_id)->first();
					if( ! count($currentApplication) )
					{
						$this->customError('Steam app not found in database. App: '.$steamUser->current_app_id.' User: '.$user->id.' '.$steamUser->username);
						$appFailureCount++;
						$currentApplication = NULL;
					}
				}

				// If the user is currently running an app and connected to a server
				if( $currentApplication && isset($steamUser->current_server_ip) )
				{
					// Find database server ID
					$currentServer = Server::where('address', $steamUser->current_server_ip)
											->where('port', $steamUser->current_server_port)
											->first();
					// Create server if it does not already exist
					if( ! count($currentServer) )
					{
						$currentServer = new Server;
						$currentServer->application_id = $currentApplication->id;
						$currentServer->address = $steamUser->current_server_ip;
						$currentServer->port = $steamUser->current_server_port;
						$currentServer->save();
					}
				}

				// Create a new state				
				$state = new State;
				$state->user_id										= $user->id;
				$state->status										= $steamUser->status;
				if( $currentApplication )	$state->application_id	= $currentApplication->id;
				if( $currentServer )		$state->server_id		= $currentServer->id;

				$state->save();
	
				$successCount++; // Only incremented if no exceptions above
			}
			catch(\Exception $e) // Catch any exceptions and print an error but continue
			{
				$this->customError('Unable to insert user state for '.$steamUser->id.' "'.$steamUser->username.'" : '. $e->getMessage());
				$failureCount++;
			}
		}
		// Provide info on results
		if( $successCount > 0 ) $this->customInfo($successCount.' Steam user states successfully added');
		if( $failureCount > 0 ) $this->customError($failureCount.' Steam user states were not added due to errors');
		if( $appFailureCount > 0 ) $this->customError($appFailureCount.' applications were not found in the database - Consider running steam:import-apps');
	}

}