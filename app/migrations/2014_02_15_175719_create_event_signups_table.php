<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSignupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_signups', function($table)
		{
			// Fields
			$table->increments('id');

			$table->integer('event_id')
				->unsigned();

			$table->integer('user_id')
				->unsigned();

			$table->timestamps();

			// Relationships
			$table->foreign('event_id')
				->references('id')
				->on('events')
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
		Schema::drop('event_signups');
	}

}
