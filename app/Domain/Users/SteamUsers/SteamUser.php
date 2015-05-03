<?php namespace Zeropingheroes\Lanager\Domain\Users\SteamUsers;

class SteamUser {

	/*
	 * Basic information
	 */
	public $id;
	public $creation_time;
	public $username;
	public $real_name;
	public $avatar_url;
	public $primary_group_id;

	/*
	 * Current status
	 */
	public $status;
	public $last_online_time;
	public $current_app_id;
	public $current_app_name;
	public $current_server_ip;
	public $current_server_port;


	/*
	 * Location
	 */
	public $location_city_id;
	public $location_country_code;
	public $location_state_code;

}