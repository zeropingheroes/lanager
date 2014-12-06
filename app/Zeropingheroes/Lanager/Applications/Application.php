<?php namespace Zeropingheroes\Lanager\Applications;

use Zeropingheroes\Lanager\BaseModel;
use Tsukanov\SteamLocomotive\Core\Tools\Store;

class Application extends BaseModel {

	protected $fillable = ['name', 'steam_app_id'];

	public function toArray()
	{
		$array = parent::toArray();
		$array['small_logo'] = $this->getLogo();
		return $array;
	}

	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	public function servers()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Servers\Server');
	}

	public function getLogo($size = 'small')
	{
		if( ! empty($this->steam_app_id) )
		{
			$locomotive = new Store();
			return $locomotive->getAppLogoURL($this->steam_app_id, $size);			
		}
		else
		{
			return ''; // Todo - logo support for non-steam apps
		}

	}

}