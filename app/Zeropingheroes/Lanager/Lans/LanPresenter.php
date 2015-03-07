<?php namespace Zeropingheroes\Lanager\Lans;

use Laracasts\Presenter\Presenter;
use ExpressiveDate, Carbon\Carbon;

class LanPresenter extends Presenter {

	public function timespan()
	{
		return 
		ExpressiveDate::make($this->start)->format( 'D jS g:ia' ) . ' - ' .
		ExpressiveDate::make($this->end)->format( 'D jS g:ia' );
	}

	public function duration()
	{
		return (new Carbon($this->end))->diffInHours(new Carbon($this->start)) . ' hours';
	}

	public function monthYear()
	{
		return ExpressiveDate::make($this->start)->format( 'F Y' );
	}

}