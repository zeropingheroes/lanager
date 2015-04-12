<?php namespace Zeropingheroes\Lanager\States;

use Laracasts\Presenter\Presenter;

class StatePresenter extends Presenter {

	/**
	 * Get the state's status text based on the status code
	 * @return string
	 */
	public function statusText()
	{
		switch ($this->status)
		{
			case '1':
				if(   is_null($this->application_id) ) return 'Online';
				if( ! is_null($this->application_id) ) return 'In Game';
			case '2':
				return 'Busy';
			case '3':
				return 'Away';
			case '4':
				return 'Snooze';
			case '5':
				return 'Looking to trade';
			case '6':
				return 'Looking to play';
			case '0':
			default:
				return 'Offline';
		}
	}

}