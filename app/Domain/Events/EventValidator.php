<?php namespace Zeropingheroes\Lanager\Domain\Events;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class EventValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'			=> 'required|max:255|unique:events,name,{id}',
		'start'			=> 'required|date_format:Y-m-d H:i:s|before:end',
		'end'			=> 'required|date_format:Y-m-d H:i:s|after:start',
		'event_type_id'	=> 'required|numeric|exists:event_types,id',
		'signup_opens'	=> 'date_format:Y-m-d H:i:s|before:signup_closes|before:end',
		'signup_closes'	=> 'date_format:Y-m-d H:i:s|after:signup_opens',
		'published'		=> 'boolean',
	];

}