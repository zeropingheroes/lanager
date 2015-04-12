<?php namespace Zeropingheroes\Lanager\Lans;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class LanValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'		=> 'required|max:255',
		'start'		=> 'required|date_format:Y-m-d H:i|before:end',
		'end'		=> 'required|date_format:Y-m-d H:i|after:start',
	];

}