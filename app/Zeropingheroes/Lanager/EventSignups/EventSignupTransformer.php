<?php namespace Zeropingheroes\Lanager\EventSignups;

use League\Fractal;

class EventSignupTransformer extends Fractal\TransformerAbstract {
	
	public function transform(EventSignup $eventSignup)
	{
		return [
			'id'			=> (int) $eventSignup->id,
			'event_id'		=> (int) $eventSignup->event_id,
			'user'			=> [
				'id'			=> $eventSignup->user->id,
				'username'		=> $eventSignup->user->username,
				'avatar'		=> $eventSignup->user->avatar,
			],
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/events/'. $eventSignup->event_id .'/signups/'. $eventSignup->id),
				]
			],
		];
	}
}