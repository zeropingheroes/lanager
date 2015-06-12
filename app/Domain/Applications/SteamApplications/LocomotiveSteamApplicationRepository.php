<?php namespace Zeropingheroes\Lanager\Domain\Applications\SteamApplications;

use Zeropingheroes\Lanager\Domain\Applications\SteamApplications\SteamApplicationContract;
use Tsukanov\SteamLocomotive\Locomotive;
use Config;

class LocomotiveSteamApplicationRepository implements SteamApplicationContract {

	protected $steamApi;

	public function __construct()
	{
		$this->steamApi = new Locomotive(Config::get('lanager/steam.apikey'));
	}

	/**
	 * Get all Steam Applications
	 *
	 * @return array
	 */
	public function getApplicationList()
	{
		$steamApps = $this->steamApi->ISteamApps->GetAppList();
		if (count($steamApps) != 0)
		{
			foreach($steamApps->applist->apps as $app)
			{
				$steamApplication = new SteamApplication;
				$steamApplication->id = $app->appid;
				$steamApplication->name = $app->name;
				$steamApplications[] = $steamApplication;
			}
			return $steamApplications;
		}
		throw new \Exception('Unable to retrieve application list from Steam');
	}

}