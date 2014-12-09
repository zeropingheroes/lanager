<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\EventTypes\EventType;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder {

	public function run()
	{
		if( DB::table('event_types')->count()) return; // don't seed if table is not empty

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
