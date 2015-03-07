<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\EventTypes\EventType;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder {

	public function run()
	{
		if( DB::table('event_types')->count()) return; // don't seed if table is not empty

		$eventTypes = [
			[
				'name'	=> 'Ceremony',
				'colour'=>	'#04c',
			],
			[
				'name'	=> 'Big Game',
				'colour'=>	'#19A601', // light green
			],
			[
				'name'	=> 'Tournament',
				'colour'=>	'#19A601', // light green
			],
			[
				'name'	=> 'Food & Drink',
				'colour'=>	'#d80',
			],
			[
				'name'	=> 'Projector',
				'colour'=>	'#55f',
			],

		];

		foreach($eventTypes as $eventType)
		{
			EventType::create($eventType);
		}
	}
}
