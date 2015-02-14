<?php namespace Zeropingheroes\Lanager\EventSignups;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class EventSignupValidator extends ValidatorAssistant {

	protected $rules = [
		'event_id'		=> 'required|exists:events,id',
		'user_id'		=> 'required|exists:users,id|unique:event_signups,user_id,NULL,id,event_id,{event_id}',
	];

	protected $messages = [
		'user_id.unique'	=> 'User already signed up to event',
	];

	protected function before()
	{
		if( isset($this->inputs['event_id']) ) $this->bind('event_id', $this->inputs['event_id']);
	}
}