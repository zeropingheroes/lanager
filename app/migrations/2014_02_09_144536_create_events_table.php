<?php

use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function($table)
		{
			// Fields
			$table->increments('id');

			$table->string('name');

			$table->text('description')
				->nullable();

			$table->timestamp('start');

			$table->timestamp('end');

			$table->integer('event_type_id')
				->unsigned()
				->nullable();

			$table->timestamps();

			// Relationships
			$table->foreign('event_type_id')
				->references('id')
				->on('event_types')
				->onUpdate('cascade')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}