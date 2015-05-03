<?php namespace Zeropingheroes\Lanager\Commands;

use Zeropingheroes\Lanager\Domain\Users\User,
	Zeropingheroes\Lanager\Domain\States\State,
	Zeropingheroes\Lanager\Domain\Applications\Application,
	Zeropingheroes\Lanager\Domain\Servers\Server,
	Zeropingheroes\Lanager\Domain\Users\SteamUsers\SteamUserContract;
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
	 */
	public function __construct(SteamUserContract $steamUserInterface)
	{
		parent::__construct();
		$this->steamUserInterface = $steamUserInterface;
	}

	/**
	 * Execute the console command.
	 *
	 */
	public function fire()
	{
		$users = User::where('visible', 1)->get()->lists('steam_id_64');

		if(count($users) == 0)
		{
			$this->info('No users in database');
			return;
		}
		
		$this->info('Requesting current status of '.count($users).' users from Steam');
		$steamUserInterface = $this->steamUserInterface->getUsers($users);

		if( count($steamUserInterface) < count($users) ) $this->error('Steam responded with '.(count($users)-count($steamUserInterface)).' fewer users than requested');

		$successCount = 0;
		$failureCount = 0;
		$missingApps = [];

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
						$missingApps[] = $steamUser->current_app_id;
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
				$this->error('Unable to insert user state for user ['.$steamUser->id.']: '. $e->getMessage());
				$failureCount++;
			}
		}
		// Provide info on results
		if( $successCount > 0 ) $this->info($successCount . ' Steam user states successfully imported' );
		if( count($missingApps) > 0 ) $this->error(count($missingApps). ' ' . str_plural('application', count($missingApps)).' missing from local database - Please run "steam:import-apps"');
	}

}