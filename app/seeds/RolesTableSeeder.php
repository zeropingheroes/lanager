<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Roles\Role;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

	/**
	 * Seed the roles table with data
	 */
	public function run()
	{
		if( DB::table('roles')->count()) return; // don't seed if table is not empty

		$roles = array(
			array('name' => 'Super Admin'),
			array('name' => 'Admin'),
			array('name' => 'Pages Admin'),
			array('name' => 'Shouts Admin'),
			array('name' => 'Events Admin'),
			array('name' => 'Playlists Admin'),
		);

		foreach($roles as $role)
		{
			Role::create($role);
		}
	}
}
