<?php namespace Zeropingheroes\Lanager\PlaylistItems;

class PlayableItemFactory {

	public function create($url, $providers)
	{
		if ( ! filter_var($url, FILTER_VALIDATE_URL) ) throw new UnplayableItemException('The URL is invalid');

		foreach ( $providers as $provider )
		{
			if ( stripos($url, $provider['domain']) !== false )
			{
				return new $provider['class']($url);
			}
		}
		throw new UnplayableItemException('The URL is not from a supported provider');
	}
}