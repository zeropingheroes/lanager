<?php namespace Zeropingheroes\Lanager\EventSignups;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class EventSignupValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'event_id'		=> 'required|exists:events,id',
		'user_id'		=> 'required|exists:users,id|unique:event_signups,user_id,NULL,id,event_id,{event_id}',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'user_id.unique'	=> 'User already signed up to event',
	];

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind the ID so it can be used in the validation rules
		if( isset($this->inputs['event_id']) ) $this->bind('event_id', $this->inputs['event_id']);
	}
}