<?php namespace Zeropingheroes\Lanager\EventSignups;

use League\Fractal;

use Zeropingheroes\Lanager\Users\UserTransformer;

class EventSignupTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = [
		'user',
	];

	public function transform(EventSignup $eventSignup)
	{
		return [
			'id'			=> (int) $eventSignup->id,
			'event_id'		=> (int) $eventSignup->event_id,
			'user_id'		=> (int) $eventSignup->user_id,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/events/'. $eventSignup->event_id .'/signups/'. $eventSignup->id),
				]
			],
		];
	}

	public function includeUser(EventSignup $eventSignup)
	{
		return $this->collection($eventSignup->user()->get(), new UserTransformer);
	}
}