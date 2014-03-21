<?php namespace Zeropingheroes\Lanager\Models;

use Carbon\Carbon;

class Event extends BaseModel {

	public static $rules = array(
		'name'			=> 'required|max:255',
		'start'			=> 'required|date_format:d/m/Y H:i',
		'end'			=> 'required|date_format:d/m/Y H:i|date_not_before_this_input:start',
		'signup_opens'	=> 'date_format:d/m/Y H:i',
		'signup_closes'	=> 'date_format:d/m/Y H:i|date_not_before_this_input:signup_opens',
		'event_type_id'	=> 'numeric|exists:event_types,id',
	);

	public function eventType()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\EventType');
	}

	public function beforeSave()
	{
		// Convert from UK date format
		$this->start = Carbon::createFromFormat('d/m/Y H:i',$this->start);
		$this->end = Carbon::createFromFormat('d/m/Y H:i',$this->end);
		if( $this->signup_opens != NULL )
		{
			$this->signup_opens = Carbon::createFromFormat('d/m/Y H:i',$this->signup_opens);
			$this->signup_closes = Carbon::createFromFormat('d/m/Y H:i',$this->signup_closes);
		}
		else
		{
			$this->signup_opens = NULL;
			$this->signup_closes = NULL;
		}
	}

	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Models\User', 'event_signups')->withTimestamps();
	}

}