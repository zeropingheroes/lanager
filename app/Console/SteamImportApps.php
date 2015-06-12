<?php namespace Zeropingheroes\Lanager\Console;

use Zeropingheroes\Lanager\Domain\Applications\Application,
	Zeropingheroes\Lanager\Domain\Applications\SteamApplications\SteamApplicationContract;
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
	 * @var SteamApplicationContract
	 */
	protected $steamApplicationInterface;

	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct(SteamApplicationContract $steamApplicationInterface)
	{
		parent::__construct();
		$this->steamApplicationInterface = $steamApplicationInterface;
	}

	/**
	 * Execute the console command.
	 *
	 */
	public function fire()
	{
		$this->info('Requesting all applications from Steam');
		$steamApplicationInterface = $this->steamApplicationInterface->getApplicationList();

		$this->info('Importing '.count($steamApplicationInterface).' applications from Steam into database (this will take up to 5 minutes)');

		$successCount = 0;
		$failureCount = 0;
		$applicationsCreated = 0;

		foreach($steamApplicationInterface as $steamApp)
		{
			$newApp = NULL;
			try
			{
				// Search for the application in the database to update it if it already exists
				$application = Application::where( 'steam_app_id', $steamApp->id )->first();
				
				if ( ! $application )
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
				
				if ( $newApp )
				{
					$applicationsCreated++;
				}
			}
			catch(\Exception $e) // Catch any exceptions and print an error but continue
			{
				$this->error('Unable to import application '.$steamApp->id.' "'.$steamApp->name.'" : '. $e->getMessage());
				$failureCount++;
			}
		}
		$this->info($successCount.' Steam applications successfully imported. New: '.$applicationsCreated);
		if ( $failureCount > 0 ) $this->error($failureCount.' Steam applications were not imported due to errors');
	}

}