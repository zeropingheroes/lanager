<?php namespace Zeropingheroes\Lanager\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use File, Config, DB, Request, Artisan;

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

		if( Config::get('lanager-core::installationCompleted') )
		{
			$this->customInfo('Installation already marked as completed');
			if (!$this->confirm('Are you sure you want to continue? [yes|no]'))
			{
				exit();
			}
		}

		$this->checkRequirements();

		$this->customInfo('Creating database structure...');
		$migrate = Artisan::call(
			'migrate',
			array('--package' => 'zeropingheroes/lanager-core')
		);
		if($migrate != 0) $this->abort('Database structure creation failure', $this->criticalMessage);

		
		$this->customInfo('Seeding database with example data...');
		$seed = Artisan::call(
			'db:seed',
			array('--class' => 'Zeropingheroes\LanagerCore\Seeds\DatabaseSeeder')
		);
		if($seed != 0) $this->abort('Database seeding failure', $this->criticalMessage);

		
		$this->customInfo('Publishing assets to public directory...');
		$publish = Artisan::call(
			'asset:publish',
			array('zeropingheroes/lanager-core',
				'patricktalmadge/bootstrapper')
		);
		if($publish != 0) $this->abort('Asset publishing failure', $this->criticalMessage);

		$this->customInfo('Importing Steam applications...');
		$import = Artisan::call(
			'steam:import-apps'
		);
		if($import != 0) $this->abort('Steam app import error', $this->criticalMessage);


		$this->customInfo('Changing session driver to database in config file...');
		$sessionConfig = $this->editConfigFile('app/config/session.php', "'driver' => 'array'", "'driver' => 'database'");
		if( ! $sessionConfig ) $this->abort('Unable to change session driver', $this->criticalMessage);

		$this->customInfo('Marking LANager as installed in config file...');
		$lanagerConfig = $this->editConfigFile('app/config/packages/zeropingheroes/lanager-core/config.php', "'installationCompleted'	=> false","'installationCompleted'	=> true");
		if( ! $sessionConfig ) $this->abort('Unable to mark installation as completed', $this->criticalMessage);

		$this->customInfo('');
		$this->customInfo('Installation completed!');
		$this->customInfo('');
		$this->customInfo('IMPORTANT: Please schedule "SteamImportUserStates" to run every minute before continuing.');
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
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
		$this->customInfo('Once you added the schedule, navigate to http://localhost and have a great LAN!');
	}

	private function checkRequirements()
	{

		$this->customInfo('Testing requirements before installation...');
		
		$this->displayCheck('PHP version greater than 5.3.7');
		$this->checkRequirement(
			version_compare( PHP_VERSION, '5.3.7' ) >= 0,
			'Install the latest stable version of PHP'
			);

		$this->displayCheck('Curl PHP module installed and enabled');
		$this->checkRequirement(
			function_exists( 'curl_version' ),
			'Install and/or enable the Curl PHP module');

		$this->displayCheck('Mcrypt PHP module installed and enabled');
		$this->checkRequirement(
			function_exists( 'mcrypt_module_open' ),
			'Install and/or enable the Mcrypt PHP module');
		
		$this->displayCheck('JSON PHP module installed and enabled');
		$this->checkRequirement(
			function_exists( 'json_encode' ),
			'Install and/or enable the JSON PHP module');
		
		$this->displayCheck('/lanager/app/storage/ directory writeable by Apache / PHP');
		$this->checkRequirement(
			is_writable( 'app/storage/' ),
			'Change the directory permissions of /lanager/app/storage/ to allow Apache & PHP write access');
		
		$this->displayCheck('PHP accessible in the system\'s path variable');
		$this->checkRequirement(
			$this->checkIfPhpInPath(),
			'Add PHP to the system\'s path variable and restart your OS');
		
		$this->displayCheck('Example config files ready for editing in /app/config/packages/');
		$this->checkRequirement(
			File::exists('app/config/packages/zeropingheroes/lanager-core/config.php') &&
			File::exists('app/config/packages/zeropingheroes/lanager-core/authority.php'),
			'Run "php artisan config:publish zeropingheroes/lanager-core"');
		
		$this->displayCheck('MySQL database accessible using the configured details');
		$this->checkRequirement(
			$this->checkDatabaseConnection(),
			'Check that the MySQL database server is running and the details in /app/config/database.php are correct');

		$this->displayCheck('Steam Web API key set');
		$this->checkRequirement(
			strlen(Config::get('lanager-core::steamWebApiKey')) == 32,
			'Enter a valid Steam Web API key in /app/config/packages/zeropingheroes/lanager-core/config.php');

		$this->displayCheck('Server able to access internet');
		$this->checkRequirement(
			$this->checkInternetConnection(),
			'Check this server can access the internet');

		$this->displayCheck('Steam Web API accessible with provided API key');
		$this->checkRequirement(
			$this->checkSteamWebApiKey(),
			'Check that Steam is up on http://steamstat.us and that the API key set in /app/config/packages/zeropingheroes/lanager-core/config.php is correct');
		
		$this->customInfo('');
		$this->customInfo('All requirements passed - continuing with installation');
		$this->customInfo('');
	}


	private function displayCheck($checkDescription)
	{
		$this->customInfo('Checking ' . $checkDescription );
	}

	private function checkRequirement($check, $messageOnFailure)
	{
		if( $check )
		{
			$this->customInfo('Passed');
		}
		else
		{
			$this->abort('Requirement not met', $messageOnFailure);
		}
	}

	private function abort($message, $actionRequired)
	{
		$this->customError('ERROR: ' . $message);
		$this->customError('INSTALLATION ABORTED');
		$this->customError($actionRequired);
		exit();


			$this->customError($failureMessage);
			exit();
	}

	/**
	 * Check if PHP is in the system's path.
	 *
	 * @return Bool
	 */
	private function checkIfPhpInPath()
	{
		$phpTest = shell_exec('php -v');
		return !empty($phpTest);
	}

	/**
	 * Check the MySQL database connection.
	 *
	 * @return Bool
	 */
	private function checkDatabaseConnection()
	{
		try
		{
			$pdo = DB::connection('mysql')->getPdo();
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
	 * @return Bool
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
	 * @return Bool
	 */
	private function checkSteamWebApiKey()
	{
		$steamApi = new \Tsukanov\SteamLocomotive\Locomotive(Config::get('lanager-core::steamWebApiKey'));
		try
		{
			$steamApi->ISteamUser->GetPlayerSummaries(array('1234'));
		}
		catch (\Guzzle\Http\Exception\ClientErrorResponseException $error)
		{
			return false;
		}
		catch (Guzzle\Http\Exception\CurlException $error)
		{
			return false;
		}
		return true;
	}

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

}
