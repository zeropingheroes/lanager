<?php namespace Zeropingheroes\Lanager\Interfaces;

interface SteamAppRepositoryInterface {

	/**
	 * Get all Steam Applications
	 *
	 * @return object SteamApplication|null
	 */
	public function getAppList();

}