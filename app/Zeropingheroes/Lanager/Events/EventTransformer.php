<?php namespace Zeropingheroes\Lanager\Events;

use League\Fractal;

class EventTransformer extends Fractal\TransformerAbstract {
	
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
			'type'			=> [
				'id'			=> $event->type['id'],
				'name'			=> $event->type['name'],
				'colour'		=> $event->type['colour'],
			],
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/events/'. $event->id),
				]
			],
		];
	}
}