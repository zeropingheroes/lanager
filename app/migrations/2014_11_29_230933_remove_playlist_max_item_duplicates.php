<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePlaylistMaxItemDuplicates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('playlists', function($table)
		{
			$table->dropColumn('max_item_duplicates');
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
			$table->tinyInteger('max_item_duplicates')
				->unsigned()
				->default(0)
				->after('max_item_duration');
		});
	}

}
