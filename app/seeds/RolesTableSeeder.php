<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Models\Role;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

	public function run()
	{
		DB::table('roles')->delete(); // Empty before we seed

		$roles = array(
			array('name' => 'SuperAdmin'),
			array('name' => 'InfoPagesAdmin'),
			array('name' => 'ShoutsAdmin'),
		);

		foreach($roles as $role)
		{
			Role::create($role);
		}
	}
}
