<?php namespace Zeropingheroes\Lanager\Helpers;

use ExpressiveDate;

class Timespan {


	/**
	 * The start date and time of the timespan.
	 *
	 * @var ExpressiveDate
	 */
	public $start;

	/**
	 * The end date and time of the timespan.
	 *
	 * @var ExpressiveDate
	 */
	public $end;

	/**
	 * The current date and time.
	 *
	 * @var ExpressiveDate
	 */
	public $now;

	/**
	 * The status indicator of whether the duration is past, present or future.
	 *
	 * @var int
	 */
	public $status;


	/**
	 * Initialise the class with a start and end date and time.
	 *
	 * @param  string  $start
	 * @param  string  $end
	 */
	public function __construct($start, $end)
	{
		$this->start = ExpressiveDate::make($start);
		$this->end = ExpressiveDate::make($end);

		if ( $this->start->greaterThan($this->end) )
		{
			throw new \Exception('Timespan start date is after end date');
		}

		$this->now = new ExpressiveDate;

		if ( $this->start->greaterThan($this->now) )
		{
			$this->status = 0;
		}

		if ( $this->start->lessOrEqualTo($this->now) && $this->end->greaterOrEqualTo($this->now) )
		{
			$this->status = 1;
		}

		if ( $this->end->lessThan($this->now) )
		{
			$this->status = 2;
		}
	}

	/**
	 * Format the timespan to read naturally, e.g.
	 * Saturday 9am to 10.30pm
	 *
	 * @return string
	 */
	public function naturalFormat()
	{
		// if event start falls on the hour, dont display minutes
		if ( $this->start->getMinute() == 0)
		{
			$startFormat = 'l ga';
		}
		else
		{
			$startFormat = 'l g:ia';
		}

		// if event start falls on the hour, dont display minutes
		if ( $this->end->getMinute() == 0)
		{
			$endFormat = 'ga';
		}
		else
		{
			$endFormat = 'g:ia';
		}

		// if event does not start and end on the same day, display the end day
		if ( $this->start->getDay() != $this->end->getDay() )
		{
			$endFormat = 'l '.$endFormat;
		}

		return $this->start->format($startFormat).' to '. $this->end->format($endFormat);
	}

}