<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class AchievementValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'			=> 'required|max:255|unique:achievements,name,{id}',
		'description'	=> 'required|max:255',
	];

}