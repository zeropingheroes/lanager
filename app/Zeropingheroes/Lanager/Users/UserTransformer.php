<?php namespace Zeropingheroes\Lanager\Users;

use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract {
	
	public function transform(User $user)
	{
		return [
			'id'			=> (int) $user->id,
			'username'		=> $user->username,
			'steam_id_64'	=> $user->steam_id_64,
			'ip'			=> $user->ip,
			'avatar'		=> $user->avatar,
			'avatar_small'	=> $user->present()->avatarSmall(),
			'avatar_medium'	=> $user->present()->avatarMedium(),
			'avatar_large'	=> $user->present()->avatarLarge(),
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/users/'. $user->id),
				]
			],
		];
	}
}