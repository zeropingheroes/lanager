<?php namespace Zeropingheroes\Lanager\UserAchievements;

use League\Fractal;

class UserAchievementTransformer extends Fractal\TransformerAbstract {
	
	public function transform(UserAchievement $userAchievement)
	{
		return [
			'id'			=> (int) $userAchievement->id,
			'user'			=> $userAchievement->user,
			'achievement'	=> $userAchievement->achievement,
			'lan'			=> $userAchievement->lan,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/user-achievements/'. $userAchievement->id),
				]
			],
		];
	}
}