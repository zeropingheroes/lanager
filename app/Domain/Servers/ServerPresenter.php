<?php namespace Zeropingheroes\Lanager\Domain\Servers;

use Laracasts\Presenter\Presenter;
use SteamBrowserProtocol;

class ServerPresenter extends Presenter {

	/**
	 * Get the server's IP and (if present) port
	 * @return string
	 */
	public function ipAndPort()
	{
		// Todo: add support for default application ports
		if ( $this->address && $this->port)
		{
			return $this->address.':'.$this->port;
		}
		if ( $this->address )
		{
			return $this->address;
		}
	}

	/**
	 * Get a URL to connect to the server through steam
	 * @return string
	 */
	public function url()
	{
		// Todo: add support for non-steam app connection URLs
		return SteamBrowserProtocol::connectToServer( $this->ipAndPort );
	}
}