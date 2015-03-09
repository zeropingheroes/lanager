<?php namespace Zeropingheroes\Lanager\Applications;

use Laracasts\Presenter\Presenter;
use Tsukanov\SteamLocomotive\Core\Tools\Store;
use Laracasts\Presenter\PresentableTrait;
use SteamBrowserProtocol;

class ApplicationPresenter extends Presenter {

	private function logo($size)
	{
		if( is_numeric($this->steam_app_id) ) return (new Store())->getAppLogoURL($this->steam_app_id, $size);
	}

	public function smallLogo()
	{
		return $this->logo('small');
	}

	public function mediumLogo()
	{
		return $this->logo('medium');
	}

	public function largeLogo()
	{
		return $this->logo('large');
	}

	public function url()
	{
		if( is_numeric( $this->steam_app_id ) )
		{
			return SteamBrowserProtocol::viewAppInStore( $this->steam_app_id );	
		}
	}

}