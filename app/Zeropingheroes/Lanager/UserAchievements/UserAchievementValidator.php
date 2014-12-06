<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class UserAchievementValidator extends ValidatorAssistant {

	protected $rules = [
		'user_id'			=> 'required|exists:users,id',
		'achievement_id'	=> 'required|exists:achievements,id',
		'lan_id'			=> 'required|exists:lans,id',
	];

}