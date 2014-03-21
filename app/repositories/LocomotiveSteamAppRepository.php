<?php namespace Zeropingheroes\Lanager\Repositories;

use Zeropingheroes\Lanager\Entities\SteamApp,
	Zeropingheroes\Lanager\Interfaces\SteamAppRepositoryInterface;
use Config;
use Tsukanov\SteamLocomotive\Locomotive;

class LocomotiveSteamAppRepository implements SteamAppRepositoryInterface {

	protected $steamApi;

	public function __construct()
	{
		$this->steamApi = new Locomotive(Config::get('lanager-core::steamWebApiKey'));
	}

	/**
	 * Get all Steam Applications
	 *
	 * @return array
	 */
	public function getAppList()
	{
		$steamApps = $this->steamApi->ISteamApps->GetAppList();
		if(count($steamApps) != 0)
		{
			foreach($steamApps->applist->apps as $steamApp)
			{
				// Create a new application entity
				$steamAppEntity = new SteamApp;
				$steamAppEntity->id = $steamApp->appid;
				$steamAppEntity->name = $steamApp->name;
				
				// Add it to an array of all entities
				$steamAppEntities[] = $steamAppEntity;
			}
			return $steamAppEntities;
		}
		else
		{
			throw new \Exception('Unable to retrieve app list from Steam');
		}
	}

}