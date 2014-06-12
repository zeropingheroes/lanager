<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Models\Role;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

	public function run()
	{
		if( DB::table('roles')->count()) return; // don't seed if table is not empty

		$roles = array(
			array('name' => 'SuperAdmin'),
			array('name' => 'InfoPagesAdmin'),
			array('name' => 'ShoutsAdmin'),
			array('name' => 'EventsAdmin'),
			array('name' => 'PlaylistsAdmin'),
		);

		foreach($roles as $role)
		{
			Role::create($role);
		}
	}
}
