<?php namespace Zeropingheroes\Lanager\Domain\Events;

use Laracasts\Presenter\Presenter;
use Timespan;
use View;

class EventPresenter extends Presenter {

	/**
	 * Get the event's human readable timespan
	 * @return string
	 */
	public function timespan()
	{
		return Timespan::summarise($this->start, $this->end);
	}

	/**
	 * Get the event's timespan relative to now
	 * @return string
	 */
	public function timespanRelativeToNow()
	{
		return Timespan::relativeToNow($this->start, $this->end);
	}

	/**
	 * Get the event's status label
	 * @return string
	 */
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
		$hover = $this->timespanRelativeToNow();
		return View::make('events.partials.status')->withStatus($status)->withClass($eventTimespanTense)->withHover($hover);
	}

	/**
	 * Get the event's signup status label
	 * @param  string $prefix Optional prefix for status
	 * @return string
	 */
	public function signupTimespanStatusLabel($prefix = '')
	{
		if ( ! is_null($this->signup_opens) )
		{
			$signupTimespanTense = Timespan::tense($this->signup_opens, $this->signup_closes);

			switch( $signupTimespanTense )
			{
				case 'past':	$status = $prefix . 'Closed';
					break;
				case 'present':	$status = $prefix . 'Open';
					break;
				case 'future':	$status = $prefix . 'Not Yet Open';
					break;
			}
			$hover = $this->signupTimespanRelativeToNow();
			return View::make('events.partials.status')->withStatus($status)->withClass($signupTimespanTense)->withHover($hover);
		}
		return View::make('events.partials.status')->withStatus('N/A')->withClass('')->withHover('Event does not allow signups');
	}

	/**
	 * Get the event's signup timespan relative to now
	 * @return string
	 */
	public function signupTimespanRelativeToNow()
	{
		if ( ! is_null($this->signup_opens) )
		{
			$words = [
				'starting' => 'Opening',
				'started' => 'Opened',
				'ending' => 'Closing',
				'ended' => 'Closed'
				];
			return Timespan::relativeToNow($this->signup_opens, $this->signup_closes, $words);
		}
	}
}