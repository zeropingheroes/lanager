<?php namespace Zeropingheroes\Lanager\Domain\UserAchievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class UserAchievementValidator extends ValidatorAssistant implements InputValidatorContract {

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