<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEventsTableEventType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE events DROP FOREIGN KEY events_event_type_id_foreign');
		DB::statement('ALTER TABLE events MODIFY COLUMN event_type_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE events ADD CONSTRAINT events_event_type_id_foreign FOREIGN KEY (event_type_id) references event_types(id)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE events DROP FOREIGN KEY events_event_type_id_foreign');
		DB::statement('ALTER TABLE events MODIFY COLUMN event_type_id INT(10) UNSIGNED NULL DEFAULT NULL');
		DB::statement('ALTER TABLE events ADD CONSTRAINT events_event_type_id_foreign FOREIGN KEY (event_type_id) references event_types(id)');
	}

}
