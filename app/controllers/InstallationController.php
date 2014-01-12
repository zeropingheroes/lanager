<?php

class InstallationController extends BaseController {

	/**
	 * The application's requirements.
	 *
	 * @var array
	 */
	private $requirements;

	/**
	* Check if the app has already been installed.
	*
	* @return InstallationController
	*/
	public function __construct()
	{
		// If the config is marked as installed then bail with a 404.
		if (Config::get('lanager-core::installationCompleted') === true)
		{
			return App::abort(404, 'Page not found');
		}
	}

	/**
	* Check requirements and display the results.
	*
	* @return Response
	*/
	public function checkRequirements()
	{
		$this->requirements['phpVersionSatisfies']			= array(
																'test'	=> 'PHP version is 5.3.7 or newer',
																'result'=> (version_compare(PHP_VERSION, '5.3.7') >= 0 ? true : 0),
																'action'=> 'Install the latest stable version of PHP'
																);
		$this->requirements['curlEnabled']					= array(
																'test'	=> 'Curl PHP module is installed and enabled',
																'result'=> (function_exists('curl_version') ? true : 0),
																'action'=> 'Install and enable the Curl PHP module'
																);
		$this->requirements['mcryptEnabled']				= array(
																'test'	=> 'Mcrypt PHP module is installed and enabled',
																'result'=> (function_exists( 'mcrypt_module_open') ? true : 0),
																'action'=> 'Install and enable the Mcrypt PHP module'
																);
		$this->requirements['jsonEnabled']					= array(
																'test'	=> 'JSON PHP module is installed and enabled',
																'result'=> (function_exists( 'json_encode') ? true : 0),
																'action'=> 'Install and enable the JSON PHP module'
																);
		$this->requirements['storageDirWriteable']			= array(
																'test'	=> '/app/storage/ directory is writeable by Apache / PHP',
																'result'=> (is_writable('../app/storage/') ? true : 0),
																'action'=> 'Change the /app/storage directory permissions to allow Apache / PHP write access'
																);
		$this->requirements['serverIsApache']				= array(
																'test'	=> 'Web server is Apache',
																'result'=> (function_exists('apache_get_modules') ? true : 0),
																'action'=> 'Switch to Apache or go hardcore and run whatever you feel like (no promises it will work!)'
																);
		if($this->requirements['serverIsApache']['result'])
		{
			$this->requirements['modRewriteEnabled']			= array(
																	'test'	=> 'Apache mod_rewrite is enabled',
																	'result'=> (in_array('mod_rewrite',apache_get_modules()) ? true : 0 ),
																	'action'=> 'Load the mod_rewrite module in apache\'s config'
																	);
			$this->requirements['htaccessLoaded']				= array(
																	'test'	=> 'Htaccess file is present in public directory and loaded',
																	'result'=> (Request::server('HTACCESS') ? true : 0 ),
																	'action'=> 'Make sure that /public/ directory has AllowOverride All enabled in Apache\'s config'
																	);
		}
		$this->requirements['phpInPath']					= array(
																'test'	=> 'PHP is accessible in the system\'s path variable',
																'result'=> ($this->checkIfPhpInPath() ? true : 0),
																'action'=> 'Add PHP to the system\'s path variable and restart'
																);
		$this->requirements['configsPublished']				= array(
																'test'	=> 'Example config files are ready for editing in /app/config/packages/',
																'result'=> ($this->checkIfConfigsPublished() ? true : 0),
																'action'=> 'From the lanager directory, publish example configs by running "php artisan config:publish zeropingheroes/lanager-core"'
																);
		$this->requirements['databaseAccessible']			= array(
																'test'	=> 'MySQL database is accessible using the configured details',
																'result'=> ($this->checkDatabaseConnection() ? true : 0),
																'action'=> 'Check that the database server is running and the details in /app/config/database.php are correct'
																);
		$this->requirements['steamWebApiKeySet']			= array(
																'test'	=> 'Steam Web API key is set',
																'result'=> (strlen(Config::get('lanager-core::steamWebApiKey')) == 32 ? true : 0),
																'action'=> 'If config files have been published, edit /app/config/packages/zeropingheroes/lanager-core/config.php and include the API key'
																);
		$this->requirements['connectedToInternet']			= array(
																'test'	=> 'Server is able to access internet',
																'result'=> ($this->checkInternetConnection() ? true : 0),
																'action'=> 'Check your server\'s internet connection'
																);
		$this->requirements['steamWebApiAccessibleWithKey']	= array(
																'test'	=> 'Steam Web API is accessible with provided API key',
																'result'=> ($this->checkSteamWebApiKey() ? true : 0),
																'action'=> 'Check that the API key set in /app/config/packages/zeropingheroes/lanager-core/config.php is correct, and that Steam is up and accessible via Curl'
																);
		$this->requirements['publicDirIsWebroot']			= array(
																'test'	=> '/public/ directory is set as the web root',
																'result'=> ($this->checkPublicDirIsWebRoot() ? true : 0),
																'action'=> 'Change the web root to the /public/ directory in Apache\'s config'
																);
		$failureCount = 0;
		
		foreach($this->requirements as $requirement)
		{
			if($requirement['result'] == 0) $failureCount++;
		}
		return View::make('installation.requirements')
					->with('title','Installation Requirements')
					->with('requirements',$this->requirements)
					->with('failureCount',$failureCount);
	}

	/**
	 * Run the installation tasks.
	 *
	 * @return Response
	 */
	public function run()
	{

		// Run migrations to create the database structure
		$migrate = Artisan::call(
			'migrate',
			array('--package' => 'zeropingheroes/lanager-core')
		);
		if($migrate != 0) return App::abort(500, 'Database migration error');

		// Run seed class to fill the database with default data
		$seed = Artisan::call(
			'db:seed',
			array('--class' => 'Zeropingheroes\LanagerCore\Seeds\DatabaseSeeder')
		);
		if($seed != 0) return App::abort(500, 'Database seeding error');

		// Publish assets to the main app's public directory
		$publish = Artisan::call(
			'asset:publish',
			array('zeropingheroes/lanager-core',
				'patricktalmadge/bootstrapper')
		);
		if($publish != 0) return App::abort(500, 'Asset publishing error');

		return View::make('installation.completed')
					->with('title','Installation Completed');

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
	 * Check if the core configs have been published.
	 *
	 * @return Bool
	 */
	private function checkIfConfigsPublished()
	{
		return (
			File::exists('../app/config/packages/zeropingheroes/lanager-core/config.php') && 
			File::exists('../app/config/packages/zeropingheroes/lanager-core/authority.php')
			);
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
		catch(PDOException $exception)
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
		if( ! $this->requirements['curlEnabled']['result'] ) return false;

		$steamApi = new Locomotive(Config::get('lanager-core::steamWebApiKey'));
		try
		{
			$steamApi->ISteamUser->GetPlayerSummaries(array('1234'));
		}
		catch (Guzzle\Http\Exception\ClientErrorResponseException $error)
		{
			return false;
		}
		catch (Guzzle\Http\Exception\CurlException $error)
		{
			return false;
		}
		return true;
	}

	/**
	 * Check that the public directory is the web root.
	 *
	 * @return Bool
	 */
	private function checkPublicDirIsWebRoot()
	{
		$url = Request::url();
		if(strpos($url,'/public/') !== false) return false;
		return true;
	}

}