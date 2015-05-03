<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleAssignedByField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_roles', function($table)
		{
			$table->integer('assigned_by')
				->unsigned()
				->nullable()
				->after('role_id');

			$table->foreign('assigned_by')
				->references('id')
				->on('users')
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
		Schema::table('user_roles', function($table)
		{
			$table->dropForeign('user_roles_assigned_by_foreign');
			$table->dropColumn('assigned_by');
		});
	}

}
