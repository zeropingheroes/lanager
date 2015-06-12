<?php namespace Zeropingheroes\Lanager\Domain\Events;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\EventTypes\EventTypeTransformer;

class EventTransformer extends TransformerAbstract {

	/**
	 * Default related resources to include in transformed output
	 * @var array
	 */
	protected $defaultIncludes = [
		'type',
	];

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
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

	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includeType(Event $event)
	{
		if ( $event->type()->count() )
		{
			return $this->item($event->type()->first(), new EventTypeTransformer);
		}
		return null;
	}
}