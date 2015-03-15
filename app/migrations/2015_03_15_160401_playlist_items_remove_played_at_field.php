<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlaylistItemsRemovePlayedAtField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('playlist_items', function($table)
		{
			$table->dropColumn('played_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('playlist_items', function($table)
		{
			$table->timestamp('played_at')
				->nullable()
				->after('skip_reason');
		});
	}

}
