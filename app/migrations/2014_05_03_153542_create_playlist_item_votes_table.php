<?php

use Illuminate\Database\Migrations\Migration;

class CreatePlaylistItemVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('playlist_item_votes', function($table)
		{
			// Fields
			$table->increments('id');

			$table->integer('playlist_item_id')
				->unsigned();

			$table->integer('user_id')
				->unsigned();

			$table->tinyInteger('vote');	// -1 for downvote, +1 for upvote

			$table->timestamps();

			// Relationships
			$table->foreign('playlist_item_id')
				->references('id')
				->on('playlist_items')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');
		});	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('playlist_item_votes');
	}

}