<?php namespace Zeropingheroes\Lanager\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config, DB, Artisan;
use Tsukanov\SteamLocomotive\Locomotive;

class Install extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lanager:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install the LANager application.';

	/**
	 * The message returned after a critical error.
	 *
	 * @var string
	 */
	protected $criticalMessage = 'Raise an issue on GitHub';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if( Config::get('lanager/config.installed') )
		{
			if (!$this->confirm('Installation already marked as completed - are you sure you want to continue? [yes|no]')) exit();
		}

		$this->checkRequirements();
		$this->runInstaller();
	}

	/**
	 * Check installation prerequisites are met before running installer.
	 *
	 * @return void
	 */
	private function checkRequirements()
	{
		$this->customInfo('Testing requirements before installation...');
		
		$this->checkRequirement('PHP version greater than 5.4', 
			version_compare( PHP_VERSION, '5.4' ) >= 0,
			'Install the latest stable version of PHP');

		$this->checkRequirement('Curl PHP module installed and enabled', 
			function_exists( 'curl_version' ),
			'Install and/or enable the Curl PHP module');

		$this->checkRequirement('Mcrypt PHP module installed and enabled', 
			function_exists( 'mcrypt_module_open' ),
			'Install and/or enable the Mcrypt PHP module');
		
		$this->checkRequirement('JSON PHP module installed and enabled', 
			function_exists( 'json_encode' ),
			'Install and/or enable the JSON PHP module');
	
		$this->checkRequirement('PHP accessible in the system\'s path variable', 
			$this->checkIfPhpInPath(),
			'Add PHP to the system\'s path variable and restart your OS');
		
		$this->checkRequirement('MySQL database accessible using the configured details', 
			$this->checkDatabaseConnection(),
			'Check that the MySQL database server is running and the details in /app/config/database.php are correct');

		$this->checkRequirement('Steam Web API key set', 
			strlen(Config::get('lanager/steam.apikey')) == 32,
			'Enter a valid Steam Web API key in /app/config/lanager/steam.php');

		$this->checkRequirement('Server able to access internet', 
			$this->checkInternetConnection(),
			'Check this server can access the internet');

		$this->checkRequirement('Steam Web API accessible with provided API key', 
			$this->checkSteamWebApiKey(),
			'Check that Steam is up on http://steamstat.us and that the API key set in /app/config/lanager/steam.php is correct');
		
		$this->customInfo('');
		$this->customInfo('All requirements passed - continuing with installation');
		$this->customInfo('');
	}

	/**
	 * Run installation steps.
	 *
	 * @return void
	 */
	private function runInstaller()
	{
		$this->customInfo('Creating database structure...');
		$migrate = Artisan::call('migrate', array('--path' => 'app/migrations', '--force' => true));
		if( $migrate != 0 ) $this->abort('Database structure creation failure', $this->criticalMessage);

		if( Config::get('lanager/config.installed') )
		{
			if( $this->confirm('Would you like to empty the database before inserting default data? [yes|no]') )
			{
				$this->emptyDatabase();
			}
		}
		$this->customInfo('Seeding database with example data...');
		$seed = Artisan::call('db:seed', array('--class' => 'Zeropingheroes\Lanager\Seeds\DatabaseSeeder', '--force' => true));
		if( $seed != 0 ) $this->abort('Database seeding failure', $this->criticalMessage);

		$this->customInfo('Publishing package assets...');
		$publish = Artisan::call('asset:publish');
		if( $publish != 0 ) $this->abort('Asset publishing failure', $this->criticalMessage);

		$this->customInfo('Importing Steam applications...');
		$import = Artisan::call('steam:import-apps');
		if( $import != 0 ) $this->abort('Steam app import error', $this->criticalMessage);

		$this->customInfo('Changing session driver to database in config file...');
		$sessionConfig = $this->editConfigFile('app/config/session.php', "'driver' => 'array'", "'driver' => 'database'");
		if( ! $sessionConfig ) $this->abort('Unable to change session driver', $this->criticalMessage);

		$this->customInfo('Setting application key...');
		$appKey = Artisan::call('key:generate');
		if( $appKey != 0 ) $this->abort('Unable to set application key', $this->criticalMessage);

		$this->customInfo('Marking LANager as installed in config file...');
		$lanagerConfig = $this->editConfigFile('app/config/lanager/config.php', "'installed'	=> false","'installed'	=> true");
		if( ! $lanagerConfig ) $this->abort('Unable to mark installation as completed', $this->criticalMessage);

		$this->customInfo('');
		$this->customInfo('Installation completed!');
		$this->customInfo('');
		$this->customInfo('IMPORTANT: Please schedule "SteamImportUserStates" to run every minute before continuing.');
		if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
		{
			$this->customInfo('Add a task for "SteamImportUserStates.bat" in Windows task scheduler');
			$this->customInfo('More info: http://support.microsoft.com/kb/226795');
		}
		else
		{
			$this->customInfo('From a terminal run "crontab -e" and add the following to the end of the file:');
			$this->customInfo('');
			$this->customInfo('*/1 * * * * /path/to/lanager/SteamImportUserStates.sh >> /dev/null 2>&1');
			$this->customInfo('');
		}
		$this->customInfo('Once you have added the schedule, navigate to this server\'s hostname and have a great LAN!');
	}

	/**
	 * Check a given requirement and continue or abort.
	 *
	 * @param string 	$checkDescription 	What is being checked
	 * @param bool 		$check 				The item being checked
	 * @param string 	$messageOnFailure 	The message to show the user
	 * @return void
	 */
	private function checkRequirement($checkDescription, $check, $messageOnFailure)
	{
		$this->customInfo('Checking ' . $checkDescription );
		if( $check )
		{
			$this->customInfo('Passed');
		}
		else
		{
			$this->abort('Requirement not met', $messageOnFailure);
		}
	}

	/**
	 * Abort the installation command and display an informational message.
	 *
	 * @param string $message 			The failure message to display
	 * @param string $actionRequired	The recommended action to recover
	 * @return void
	 */
	private function abort($message, $actionRequired)
	{
		$this->customError('ERROR: ' . $message);
		$this->customError('INSTALLATION ABORTED');
		$this->customError($actionRequired);
		exit();
	}

	/**
	 * Check if PHP is in the system's path.
	 *
	 * @return bool
	 */
	private function checkIfPhpInPath()
	{
		$phpTest = shell_exec('php -v');
		return !empty($phpTest);
	}

	/**
	 * Check the MySQL database connection.
	 *
	 * @return bool
	 */
	private function checkDatabaseConnection()
	{
		try
		{
			DB::connection('mysql')->getPdo();
		}
		catch(\PDOException $exception)
		{
			return false;
		}
		return true;
	}

	/**
	 * Check that the server has internet access.
	 *
	 * @return bool
	 */
	private function checkInternetConnection()
	{
		$connectionTest = @fsockopen("www.google.com", 80); //website and port
		if($connectionTest)
		{
			fclose($connectionTest);
			return true;
		}
		return false;
	}

	/**
	 * Check that the configured Steam web API key is valid.
	 *
	 * @return bool
	 */
	private function checkSteamWebApiKey()
	{
		$steamApi = new Locomotive(Config::get('lanager/steam.apikey'));
		try
		{
			$steamApi->ISteamUser->GetPlayerSummaries(array('1234'));
		}
		catch (\Guzzle\Http\Exception\ClientErrorResponseException $error)
		{
			return false;
		}
		catch (\Guzzle\Http\Exception\CurlException $error)
		{
			return false;
		}
		return true;
	}

	/**
	 * Edit a config file using find and replace
	 *
	 * @param	string 	$path 		Full path to the config file
	 * @param	string 	$find 		Text to find
	 * @param	string 	$replace 	Text to replace
	 * @return	bool
	 */
	private function editConfigFile($path, $find, $replace)
	{
		$fileContents = file_get_contents($path);
		$fileContents = str_replace($find, $replace, $fileContents, $replacementsMade);
		
		if( $replacementsMade == 0 )
		{
			// Check if replacement has already been made
			str_replace($replace, $replace, $fileContents, $replacementsMade);
			if( $replacementsMade == 0 )
			{
				$this->abort('Could not find text "' . $find . '" in "' . $path . '"', $this->criticalMessage);
			}
		}
		return file_put_contents($path, $fileContents);
	}

	/**
	 * Empty database tables.
	 *
	 * @return void
	 */
	private function emptyDatabase()
	{
		$this->customInfo('Emptying database tables...');
		$tables = array(
			'achievements',
			'applications',
			'awards',
			'events',
			'event_signups',
			'event_types',
			'info_pages',
			'lans',
			'permissions',
			'playlists',
			'playlist_items',
			'playlist_item_votes',
			'roles',
			'role_user',
			'servers',
			'sessions',
			'shouts',
			'states',
			'users',
			);
		foreach($tables as $table)
		{
			DB::table($table)->delete();
		}
	}

}
