<?php namespace Zeropingheroes\Lanager\States;

interface StateContract {

	/**
	 * Get states for specified user(s)
	 *
	 * @param  array   $users|null
	 * @return array|object State
	 */
	public function getUserStates($users = null);

	/**
	 * Get applications currently being used by users
	 *
	 * @return array
	 */
	public function getApplicationUsage($applications = null, $timestamp = null);

	/**
	 * Get servers currently being used by users
	 *
	 * @return array
	 */
	public function getServerUsage($servers = null, $timestamp = null);

}