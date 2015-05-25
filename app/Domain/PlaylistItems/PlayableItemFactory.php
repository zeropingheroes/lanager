<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use DomainException;

class PlayableItemFactory {

	/**
	 * Create the class responsible for getting the playable item's metadata
	 * @param  string $url              Playable item's URL
	 * @param  array  $providers        Array of supported providers
	 * @return object PlayableItem      The playable item
	 * @throws DomainException  When the URL is from an unsupported provider or is invalid
	 */
	public function create($url, $providers)
	{
		if ( ! filter_var($url, FILTER_VALIDATE_URL) ) throw new DomainException('The URL is invalid');

		foreach ( $providers as $provider )
		{
			if ( stripos($url, $provider['domain']) !== false )
			{
				return new $provider['class']($url);
			}
		}
		throw new DomainException('The URL is not from a supported provider');
	}
}