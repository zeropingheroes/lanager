<?php namespace Zeropingheroes\Lanager\UserAchievements;

use League\Fractal;

class UserAchievementTransformer extends Fractal\TransformerAbstract {
	
	public function transform(UserAchievement $userAchievement)
	{
		return [
			'id'			=> (int) $userAchievement->id,
			'user_id'		=> (int) $userAchievement->user_id,
			'achievement_id'=> (int) $userAchievement->achievement_id,
			'lan_id'		=> (int) $userAchievement->lan_id,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/user-achievements/'. $userAchievement->id),
				]
			],
		];
	}
}