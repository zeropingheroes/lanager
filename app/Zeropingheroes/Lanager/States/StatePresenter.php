<?php namespace Zeropingheroes\Lanager\States;

use Laracasts\Presenter\Presenter;
use SteamBrowserProtocol;

class StatePresenter extends Presenter {

	public function statusText()
	{
		switch ($this->status)
		{
			case '1':
				if( ! is_null($this->application_id) ) return 'In Game'; // Online AND In-game != Online AND away
				return 'Online';
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

	public function applicationLink()
	{
		if( is_numeric( $this->application_id ) )
		{
			return link_to( SteamBrowserProtocol::viewAppInStore( $this->application->steam_app_id ), $this->application->name );	
		}
	}

	public function serverLink()
	{
		if( is_numeric( $this->server_id ) )
		{
			return link_to( SteamBrowserProtocol::connectToServer( $this->server->getFullAddress() ), $this->server->getFullAddress() );
		}
	}
}