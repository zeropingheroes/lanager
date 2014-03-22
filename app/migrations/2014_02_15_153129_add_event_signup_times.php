<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventSignupTimes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function($table)
		{
			$table->timestamp('signup_opens')
				->after('end')
				->nullable();
			$table->timestamp('signup_closes')
				->after('signup_opens')
				->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function($table)
		{
			$table->dropColumn('signup_opens');
			$table->dropColumn('signup_closes');
		});
	}

}
