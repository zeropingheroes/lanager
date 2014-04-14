<?php

use Illuminate\Database\Migrations\Migration;

class AddSteamUserVisibilityField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->integer('steam_visibility')
				->default(1)
				->unsigned()
				->after('steam_id_64');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('steam_visibility');
		});
	}

}