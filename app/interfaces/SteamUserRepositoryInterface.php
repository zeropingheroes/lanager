<?php
namespace Zeropingheroes\Lanager\Interfaces;


interface SteamUserRepositoryInterface {

	/**
	 * Get a single SteamUser by ID
	 *
	 * @param  string   $id
	 * @return object SteamUser|null
	 */
	public function getUser($id);

	/**
	 * Get many SteamUsers by ID
	 *
	 * @param  array   $ids
	 * @return array
	 */
    public function getUsers(array $ids);

}