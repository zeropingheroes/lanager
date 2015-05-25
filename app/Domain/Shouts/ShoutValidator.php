<?php namespace Zeropingheroes\Lanager\Domain\Shouts;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class ShoutValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'user_id'		=> 'required|exists:users,id',
		'content'		=> 'required|max:140',
		'pinned'		=> 'boolean',
	];

}