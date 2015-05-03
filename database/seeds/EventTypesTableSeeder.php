<?php

use	Zeropingheroes\Lanager\Domain\EventTypes\EventType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder {

	/**
	 * Seed the event types table with data
	 */
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
				'colour'=>	'#A0000C', // red
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
