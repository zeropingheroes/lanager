<?php namespace Zeropingheroes\Lanager\Seeds;

use Illuminate\Database\Seeder;
use Eloquent;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('Zeropingheroes\Lanager\Seeds\RolesTableSeeder');
		$this->call('Zeropingheroes\Lanager\Seeds\PagesTableSeeder');
		$this->call('Zeropingheroes\Lanager\Seeds\EventTypesTableSeeder');
		$this->call('Zeropingheroes\Lanager\Seeds\PlaylistsTableSeeder');
	}

}