<?php namespace Zeropingheroes\Lanager\EventTypes;

use League\Fractal;

class EventTypeTransformer extends Fractal\TransformerAbstract {
	
	public function transform(EventType $eventType)
	{
		return [
			'id'			=> (int) $eventType->id,
			'name'			=> $eventType->name,
			'colour'		=> $eventType->colour,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/event-types/'. $eventType->id),
				]
			],
		];
	}
}