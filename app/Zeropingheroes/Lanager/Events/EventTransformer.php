<?php namespace Zeropingheroes\Lanager\Events;

use League\Fractal;

use Zeropingheroes\Lanager\EventTypes\EventTypeTransformer;

class EventTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = [
		'type',
	];
	
	public function transform(Event $event)
	{
		return [
			'id'			=> (int) $event->id,
			'name'			=> $event->name,
			'description'	=> $event->description,
			'start'			=> date('c',strtotime($event->start)),
			'end'			=> date('c',strtotime($event->end)),
			'signup_opens'	=> ( empty($event->signup_opens) ? null : date('c',strtotime($event->signup_opens)) ),
			'signup_closes'	=> ( empty($event->signup_closes) ? null : date('c',strtotime($event->signup_closes)) ),
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/events/'. $event->id),
				]
			],
		];
	}

	public function includeType(Event $event)
	{
		if( $event->type()->count() )
		{
			return $this->item($event->type()->first(), new EventTypeTransformer);
		}
		return null;
	}
}