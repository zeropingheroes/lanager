<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class EventTypeValidator extends ValidatorAssistant implements InputValidatorContract{

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'			=> 'required|max:255|unique:event_types,name,{id}',
		'colour'		=> 'hex_colour',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'colour.hex_colour'	=> 'Colour must be a valid RGB hex value',
	];

	/**
	 * Validate a colour is a valid hexadecimal string
	 * @param  string $attribute  Name of the input field
	 * @param  string $value      Value of the input field
	 * @param  array  $parameters
	 * @return bool               True if validation passes, false otherwise
	 */
	public function customHexColour($attribute, $value, $parameters)
	{
		return preg_match('/#[a-f0-9]{3}(?:[a-f0-9]{3})?\b/i', $value);
	}

}