<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('playlist_items', function($table)
		{
			// Fields
			$table->increments('id');

			$table->integer('playlist_id')
				->unsigned();

			$table->integer('user_id')
				->unsigned();

			$table->text('url');

			$table->string('title');

			$table->integer('playback_state')
				->default(0)
				->unsigned();

			$table->integer('duration')
				->unsigned();

			$table->timestamps();

			// Relationships
			$table->foreign('playlist_id')
				->references('id')
				->on('playlists')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('playlist_items');
	}

}
