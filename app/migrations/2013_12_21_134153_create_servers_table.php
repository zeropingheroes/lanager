<?php

use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('servers', function($table)
		{
			// Fields
			$table->increments('id');

			$table->integer('application_id')
				->unsigned();

			$table->string('name')
				->nullable();

			$table->string('address');	// ipv4, hostname or ipv6

			$table->string('port');

			$table->boolean('pinned');

			$table->timestamps();

			// Relationships
			$table->foreign('application_id')
				->references('id')
				->on('applications')
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
		Schema::drop('servers');
	}

}