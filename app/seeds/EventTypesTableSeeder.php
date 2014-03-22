<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Models\EventType;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder {

	public function run()
	{
		DB::table('event_types')->delete(); // Empty before we seed

		$eventTypes = array(
			array(
				'name'	=> 'Ceremony',
				'colour'=>	'',
			),
			array(
				'name'	=> 'Big Game',
				'colour'=>	'#19A601', // light green
			),
			array(
				'name'	=> 'Tournament',
				'colour'=>	'#19A601', // light green
			),
			array(
				'name'	=> 'Food',
				'colour'=>	'',
			),

		);

		foreach($eventTypes as $eventType)
		{
			EventType::create($eventType);
		}
	}
}
