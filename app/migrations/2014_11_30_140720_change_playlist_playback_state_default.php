<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePlaylistPlaybackStateDefault extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE playlists MODIFY COLUMN playback_state INT(10) UNSIGNED NOT NULL DEFAULT 1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE playlists MODIFY COLUMN playback_state INT(10) UNSIGNED NOT NULL DEFAULT 0');
	}

}
