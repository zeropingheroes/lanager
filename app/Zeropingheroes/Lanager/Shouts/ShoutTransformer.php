<?php namespace Zeropingheroes\Lanager\Shouts;

use League\Fractal;

class ShoutTransformer extends Fractal\TransformerAbstract {
	
	public function transform(Shout $shout)
	{
		return [
			'id'			=> (int) $shout->id,
			'content'		=> $shout->content,
			'pinned'		=> (bool) $shout->pinned,
			'user'			=> [
				'id'			=> $shout->user->id,
				'username'		=> $shout->user->username,
				'avatar'		=> $shout->user->avatar,
			],
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/shouts/'. $shout->id),
				]
			],
		];
	}
}