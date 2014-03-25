<?php

use Illuminate\Database\Migrations\Migration;

class AddPLaylistPlaybackState extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('playlists', function($table)
		{
			$table->integer('playback_state')
				->default(0)
				->unsigned()
				->after('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('playlists', function($table)
		{
			$table->dropColumn('playback_state');
		});
	}

}