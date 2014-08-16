<?php namespace Zeropingheroes\Lanager\Events;

use Laracasts\Presenter\Presenter;
use Timespan;

class EventPresenter extends Presenter {

	public function timespan()
	{
		return Timespan::summarise($this->start, $this->end);
	}

	public function timespanRelativeToNow()
	{
		return Timespan::relativeToNow($this->start, $this->end);
	}

	public function timespanTense()
	{
		$eventTimespanStatus = Timespan::tense($this->start, $this->end);
		switch( $eventTimespanStatus )
		{
			case 'past':	return 'Ended';
			case 'present':	return 'In Progress';
			case 'future':	return 'Upcoming';
		}
	}

	public function signupTimespanTense()
	{
		$signupTimespanTense = Timespan::tense($this->signup_opens, $this->signup_closes);
		switch( $signupTimespanTense )
		{
			case 'past':	return 'Closed';
			case 'present':	return 'Open';
			case 'future':	return 'Not yet open';
		}
	}

	public function signupTimespanRelativeToNow()
	{
		$words = array(
			'starting' => 'Opening',
			'started' => 'Opened',
			'ending' => 'closing',
			'ended' => 'Closed'
			);
		return Timespan::relativeToNow($this->signup_opens, $this->signup_closes, $words);
	}
}