<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class EventSignupValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'event_id'		=> 'required|exists:events,id',
		'user_id'		=> 'required|exists:users,id',
	];


}