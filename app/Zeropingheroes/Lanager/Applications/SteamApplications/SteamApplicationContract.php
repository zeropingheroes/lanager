<?php namespace Zeropingheroes\Lanager\Applications\SteamApplications;

interface SteamApplicationContract {

	/**
	 * Get all Steam Applications
	 *
	 * @return object SteamApplication|null
	 */
	public function getApplicationList();

}