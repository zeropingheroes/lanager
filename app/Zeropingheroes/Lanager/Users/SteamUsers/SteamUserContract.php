<?php namespace Zeropingheroes\Lanager\Users\SteamUsers;

interface SteamUserContract {

	/**
	 * Get a single SteamUser by ID
	 *
	 * @param  string   $steamId64
	 * @return object SteamUser|null
	 */
	public function getUser($steamId64);

	/**
	 * Get many SteamUsers by ID
	 *
	 * @param  array   $steamId64s
	 * @return array
	 */
    public function getUsers(array $steamId64s);

}