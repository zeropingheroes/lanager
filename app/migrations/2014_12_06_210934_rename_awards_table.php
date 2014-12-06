<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAwardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('awards');

		Schema::create('user_achievements', function($table)
		{
			// Fields
			$table->increments('id')
				->unsigned();

			$table->integer('user_id')
				->unsigned();

			$table->integer('achievement_id')
				->unsigned();
			
			$table->integer('lan_id')
				->unsigned();

			$table->timestamps();

			// Relationships
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('achievement_id')
				->references('id')
				->on('achievements')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('lan_id')
				->references('id')
				->on('lans')
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
		Schema::drop('user_achievements');

		Schema::create('awards', function($table)
		{
			// Fields
			$table->increments('id')
				->unsigned();

			$table->integer('user_id')
				->unsigned();

			$table->integer('achievement_id')
				->unsigned();
			
			$table->integer('lan_id')
				->unsigned()
				->nullable();

			$table->timestamps();

			// Relationships
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('achievement_id')
				->references('id')
				->on('achievements')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('lan_id')
				->references('id')
				->on('lans')
				->onUpdate('cascade')
				->onDelete('set null');

		});
	}

}
