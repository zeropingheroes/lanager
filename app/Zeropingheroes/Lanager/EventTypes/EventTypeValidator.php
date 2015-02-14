<?php namespace Zeropingheroes\Lanager\EventTypes;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class EventTypeValidator extends ValidatorAssistant {

	protected $rules = [
		'name'			=> 'required|max:255',
		'colour'		=> 'max:255|hex_colour',
	];

	protected $messages = [
		'colour.hex_colour'	=> 'Colour must be a valid RGB hex value',
	];

	public function customHexColour($attribute, $value, $parameters)
	{
		return preg_match('/#[a-f0-9]{3}(?:[a-f0-9]{3})?\b/i', $value);
	}

}