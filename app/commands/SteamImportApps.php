<?php namespace Zeropingheroes\Lanager\Commands;

use Zeropingheroes\Lanager\Models\Application,
	Zeropingheroes\Lanager\Interfaces\SteamAppRepositoryInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SteamImportApps extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'steam:import-apps';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieve and store all Steam applications.';

	/**
	 * The steam app interface.
	 *
	 * @var SteamApp
	 */
	protected $steamApps;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(SteamAppRepositoryInterface $steamApps)
	{
		parent::__construct();
		$this->steamApps = $steamApps;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->customInfo('Requesting all applications from Steam');
		$steamApps = $this->steamApps->getAppList();

		$this->customInfo('Importing '.count($steamApps).' applications from Steam into database');
		
		$successCount = 0;
		$failureCount = 0;
		$applicationsCreated = 0;

		foreach($steamApps as $steamApp)
		{
			$newApp = NULL;
			try
			{
				// Search for the application in the database to update it if it already exists
				$application = Application::where( 'steam_app_id', $steamApp->id )->first();
				
				if( !$application )
				{
					$application = new Application;
					$newApp = true;
				}

				// Assign fields
				$application->steam_app_id = $steamApp->id;
				$application->name = $steamApp->name;
				
				// Insert new OR update existing with fields above
				$application->save();
				
				$successCount++;
				
				if( $newApp )
				{
					$applicationsCreated++;
				}
			}
			catch(\Exception $e) // Catch any exceptions and print an error but continue
			{
				$this->customError('Unable to import application '.$steamApp->id.' "'.$steamApp->name.'" : '. $e->getMessage());
				$failureCount++;
			}
		}
		$this->customInfo($successCount.' Steam applications successfully imported. New: '.$applicationsCreated);
		if( $failureCount > 0 ) $this->customError($failureCount.' Steam applications were not imported due to errors');
	}

}