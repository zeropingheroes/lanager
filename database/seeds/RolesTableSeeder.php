<?php

use	Zeropingheroes\Lanager\Domain\Roles\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

	/**
	 * Seed the roles table with data
	 */
	public function run()
	{
		if( DB::table('roles')->count()) return; // don't seed if table is not empty

		$roles = [
			['name' => 'Super Admin'],
			['name' => 'Admin'],
			['name' => 'Pages Admin'],
			['name' => 'Shouts Admin'],
			['name' => 'Events Admin'],
		);

		foreach($roles as $role)
		{
			Role::create($role);
		}
	}
}
