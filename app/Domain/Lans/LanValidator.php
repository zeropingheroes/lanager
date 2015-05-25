<?php namespace Zeropingheroes\Lanager\Domain\Lans;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class LanValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'		=> 'required|max:255|unique:lans,name,{id}',
		'start'		=> 'required|date_format:Y-m-d H:i:s|before:end',
		'end'		=> 'required|date_format:Y-m-d H:i:s|after:start',
	];

}