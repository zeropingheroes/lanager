<?php namespace Zeropingheroes\Lanager\Helpers;

use ExpressiveDate;

class Duration {

	/**
	 * The duration in seconds.
	 *
	 * @var int
	 */
	public $duration;

	/**
	 * The hours section of the duration format.
	 *
	 * @var int
	 */
	public $hoursSection;

	/**
	 * The minutes section of the duration format.
	 *
	 * @var int
	 */
	public $minutesSection;

	/**
	 * The seconds section of the duration format.
	 *
	 * @var int
	 */
	public $secondsSection;

	/**
	 * Initialise the class with a duration in seconds.
	 *
	 * @param  int  $duration
	 */
	public function __construct($duration)
	{
		$this->duration = $duration;
		$this->hoursSection = (int) floor($this->duration / 3600);
		$this->minutesSection = (int) $this->duration / 60 % 60;
		$this->secondsSection = (int) $this->duration % 60;
	}

	/**
	 * Format the duration as a short string, e.g.
	 * 4h 3d 1s
	 *
	 * @return string
	 */
	public function shortFormat()
	{
		if( $this->hoursSection >= 1 )
		{
			return sprintf('%sh %sm %ss', $this->hoursSection, $this->minutesSection, $this->secondsSection);
		}
		if( $this->minutesSection >= 1 )
		{
			return sprintf('%sm %ss', $this->minutesSection, $this->secondsSection);
		}
		
		return sprintf('%ss', $this->secondsSection);
	}

	/**
	 * Format the duration mimicing a LCD display, e.g.
	 * 04:03:01
	 *
	 * @return string
	 */
	public function lcdFormat()
	{
		return sprintf('%02d:%02d:%02d', $this->hoursSection, $this->minutesSection, $this->secondsSection);
	}

	/**
	 * Format the duration as a long string e.g.
	 * 4 hours 3 days 1 second
	 *
	 * @return string
	 */
	public function longFormat()
	{
		if( $this->hoursSection >= 1 )
		{
			return sprintf(
				' %s ' . str_plural('hour', $this->hoursSection) .
				' %s ' . str_plural('minute', $this->minutesSection) .
				' %s ' . str_plural('second', $this->secondsSection)
				, $this->hoursSection, $this->minutesSection, $this->secondsSection);
		}
		if( $this->minutesSection >= 1 )
		{
			return sprintf(
				' %s ' . str_plural('minute', $this->minutesSection) .
				' %s ' . str_plural('second', $this->secondsSection)
				, $this->minutesSection, $this->secondsSection);
		}
		
		return sprintf(' %s ' . str_plural('second', $this->secondsSection), $this->secondsSection);
	}

}