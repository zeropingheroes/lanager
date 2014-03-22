<?php

use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('states', function($table)
		{
			// Fields
			$table->increments('id');

			$table->integer('user_id')
				->unsigned();

			$table->integer('application_id')
				->unsigned()
				->nullable(); // nullable to prevent circular dependencies

			$table->integer('server_id')
				->unsigned()
				->nullable();  // nullable to prevent circular dependencies

			$table->smallInteger('status')
				->unsigned();

			$table->timestamps();

			// Relationships
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('application_id')
				->references('id')
				->on('applications')
				->onUpdate('cascade')
				->onDelete('set null');

			$table->foreign('server_id')
				->references('id')
				->on('servers')
				->onUpdate('cascade')
				->onDelete('set null');

			// Indexes
			$table->index(array('user_id', 'created_at'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('states');
	}

}
