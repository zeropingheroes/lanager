<?php namespace Zeropingheroes\Lanager\Lans;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class LanValidator extends ValidatorAssistant {

	protected $rules = [
		'name'		=> 'required|max:255',
		'start'		=> 'required|date_format:Y-m-d H:i:s|before:end',
		'end'		=> 'required|date_format:Y-m-d H:i:s|after:start',
	];

}