<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class AchievementValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'			=> 'required|max:255|unique:achievements,name,{id}',
		'description'	=> 'required|max:255',
	];

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind the ID so it can be used in the validation rules
		if( isset($this->inputs['id']) ) $this->bind('id', $this->inputs['id']);
	}

}