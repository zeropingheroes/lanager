<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shouts', function($table)
		{		
			// Fields
			$table->increments('id');

			$table->integer('user_id')
				->unsigned();

			$table->string('content');

			$table->boolean('pinned');

			$table->timestamps();

			// Relationships
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
		Schema::drop('shouts');
	}

}