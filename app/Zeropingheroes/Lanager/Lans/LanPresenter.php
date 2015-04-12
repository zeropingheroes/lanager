<?php namespace Zeropingheroes\Lanager\Lans;

use Laracasts\Presenter\Presenter;
use ExpressiveDate, Carbon\Carbon;

class LanPresenter extends Presenter {

	/**
	 * Get the LAN's timespan in human readable format
	 * @return string
	 */
	public function timespan()
	{
		return 
		ExpressiveDate::make($this->start)->format( 'D jS g:ia' ) . ' - ' .
		ExpressiveDate::make($this->end)->format( 'D jS g:ia' );
	}

	/**
	 * Get the LAN's duration in hours
	 * @return string
	 */
	public function duration()
	{
		return (new Carbon($this->end))->diffInHours(new Carbon($this->start)) . ' hours';
	}

	/** 
	 * Get the LAN's month and year
	 * @return string
	 */
	public function monthYear()
	{
		return ExpressiveDate::make($this->start)->format( 'F Y' );
	}

}