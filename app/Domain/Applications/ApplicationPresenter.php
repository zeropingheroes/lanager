<?php namespace Zeropingheroes\Lanager\Domain\Applications;

use Laracasts\Presenter\Presenter;
use Tsukanov\SteamLocomotive\Core\Tools\Store;
use Laracasts\Presenter\PresentableTrait;
use SteamBrowserProtocol;

class ApplicationPresenter extends Presenter {

	/**
	 * Get the application's logo image URL
	 * @param  string $size Small/medium/large
	 * @return string       Logo image URL
	 */
	private function logo($size)
	{
		if( is_numeric($this->steam_app_id) ) return (new Store())->getAppLogoURL($this->steam_app_id, $size);
	}

	/**
	 * Get the application's small logo image URL
	 * @return string       Logo image URL
	 */
	public function smallLogo()
	{
		return $this->logo('small');
	}

	/**
	 * Get the application's medium logo image URL
	 * @return string       Logo image URL
	 */
	public function mediumLogo()
	{
		return $this->logo('medium');
	}

	/**
	 * Get the application's large logo image URL
	 * @return string       Logo image URL
	 */
	public function largeLogo()
	{
		return $this->logo('large');
	}

	/**
	 * Get the application's Steam store URL
	 * @return string       Steam store URL
	 */
	public function url()
	{
		if( is_numeric( $this->steam_app_id ) )
		{
			return SteamBrowserProtocol::viewAppInStore( $this->steam_app_id );	
		}
	}

}