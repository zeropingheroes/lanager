<?php

use Illuminate\Database\Migrations\Migration;

class AddPlaylistItemSkipReasonField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('playlist_items', function($table)
		{
			$table->string('skip_reason')
				->nullable()
				->after('duration');
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
			$table->dropColumn('skip_reason');
		});
	}

}