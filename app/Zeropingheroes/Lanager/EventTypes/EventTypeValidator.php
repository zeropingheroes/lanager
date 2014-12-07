<?php namespace Zeropingheroes\Lanager\EventTypes;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class EventTypeValidator extends ValidatorAssistant {

	protected $rules = [
		'name'			=> 'required|max:255',
		'colour'		=> 'max:255',
	];

}