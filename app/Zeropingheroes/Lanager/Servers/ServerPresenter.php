<?php namespace Zeropingheroes\Lanager\Servers;

use Laracasts\Presenter\Presenter;
use SteamBrowserProtocol;

class ServerPresenter extends Presenter {

	public function ipAndPort()
	{
		// Todo: add support for default application ports
		if( $this->address && $this->port)
		{
			return $this->address.':'.$this->port;
		}
		if( $this->address )
		{
			return $this->address;
		}
	}

	public function url()
	{
		// Todo: add support for non-steam app connection URLs
		return SteamBrowserProtocol::connectToServer( $this->ipAndPort );
	}
}