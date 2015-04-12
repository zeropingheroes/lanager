<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class UserAchievementValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'user_id'			=> 'required|exists:users,id',
		'achievement_id'	=> 'required|exists:achievements,id',
		'lan_id'			=> 'required|exists:lans,id',
	];

}