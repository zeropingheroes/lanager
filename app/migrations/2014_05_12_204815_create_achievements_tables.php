<?php

use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('achievements', function($table)
		{
			// Fields
			$table->increments('id')
				->unsigned();

			$table->string('name');

			$table->string('description')
				->nullable();

			$table->text('image')
				->nullable();

			$table->tinyInteger('visible')
				->default(1)
				->unsigned();

			$table->timestamps();

		});

		Schema::create('lans', function($table)
		{
			// Fields
			$table->increments('id')
				->unsigned();

			$table->string('name');

			$table->timestamp('start');

			$table->timestamp('end');

			$table->timestamps();

		});

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

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('awards');
		Schema::drop('achievements');
		Schema::drop('lans');
	}
}