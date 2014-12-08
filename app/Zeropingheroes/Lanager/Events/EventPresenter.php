<?php namespace Zeropingheroes\Lanager\Events;

use Laracasts\Presenter\Presenter;
use Timespan;
use View;

class EventPresenter extends Presenter {

	public function timespan()
	{
		return Timespan::summarise($this->start, $this->end);
	}

	public function timespanRelativeToNow()
	{
		return Timespan::relativeToNow($this->start, $this->end);
	}

	public function timespanStatusLabel()
	{
		$eventTimespanTense = Timespan::tense($this->start, $this->end);
		switch( $eventTimespanTense )
		{
			case 'past':	$status = 'Ended';
				break;
			case 'present':	$status = 'In Progress';
				break;
			case 'future':	$status = 'Upcoming';
				break;
		}
		return View::make('events.partials.status')->withStatus($status)->withClass($eventTimespanTense);
	}

	public function signupTimespanStatusLabel()
	{
		$signupTimespanTense = Timespan::tense($this->signup_opens, $this->signup_closes);

		switch( $signupTimespanTense )
		{
			case 'past':	$status = 'Closed';
				break;
			case 'present':	$status = 'Open';
				break;
			case 'future':	$status = 'Not Yet Open';
				break;
		}
		return View::make('events.partials.status')->withStatus($status)->withClass($signupTimespanTense);
	}

	public function signupTimespanRelativeToNow()
	{
		$words = array(
			'starting' => 'Opening',
			'started' => 'Opened',
			'ending' => 'Closing',
			'ended' => 'Closed'
			);
		return Timespan::relativeToNow($this->signup_opens, $this->signup_closes, $words);
	}
}