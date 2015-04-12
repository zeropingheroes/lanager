<?php namespace Zeropingheroes\Lanager\Shouts;

use League\Fractal;

use Zeropingheroes\Lanager\Users\UserTransformer;

class ShoutTransformer extends Fractal\TransformerAbstract {

	/**
	 * Default related resources to include in transformed output
	 * @var array
	 */
	protected $defaultIncludes = [
		'user',
	];

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(Shout $shout)
	{
		return [
			'id'			=> (int) $shout->id,
			'content'		=> $shout->content,
			'pinned'		=> (bool) $shout->pinned,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/shouts/'. $shout->id),
				]
			],
		];
	}

	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includeUser(Shout $shout)
	{
		return $this->item($shout->user()->first(), new UserTransformer);
	}

}