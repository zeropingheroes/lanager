<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use League\Fractal\TransformerAbstract;

class EventTypeTransformer extends TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(EventType $eventType)
	{
		return [
			'id'			=> (int) $eventType->id,
			'name'			=> $eventType->name,
			'colour'		=> $eventType->colour,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/event-types/'. $eventType->id),
				]
			],
		];
	}
}