<?php namespace Zeropingheroes\Lanager\Achievements;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class AchievementValidator extends ValidatorAssistant {

	protected $rules = [
		'name'			=> 'required|max:255|unique:achievements,name,{id}',
		'description'	=> 'required|max:255',
	];

	protected function before()
	{
		if( isset($this->inputs['id']) ) $this->bind('id', $this->inputs['id']);
	}

}