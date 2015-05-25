<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use DomainException;

class YouTubeVideo extends PlayableItem {

	/**
	 * YouTube's GData API URL
	 */
	const API_URL = 'http://gdata.youtube.com/feeds/api/videos/';

	/**
	 * Format we require to pull the data into PHP
	 */
	const API_FORMAT_QUERY = '?format=5&alt=json';

	/**
	 * Collect the item's metadaa and construct the object
	 * @param string $url The YouTube video's URL
	 */
	public function __construct( $url )
	{
		parse_str( parse_url( $url, PHP_URL_QUERY ), $queryString);
		
		if( empty($queryString['v']) ) throw new DomainException('The video ID is invalid');

		$videoApiUrl = self::API_URL.$queryString['v'].self::API_FORMAT_QUERY;

		// TODO: refactor to use guzzle library
		
		$connection = curl_init();
		curl_setopt_array($connection, array(
						CURLOPT_URL => $videoApiUrl,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_AUTOREFERER => true,
						CURLOPT_CONNECTTIMEOUT => 120,
						CURLOPT_TIMEOUT => 5));
		$responseData = json_decode(curl_exec($connection), true);
		$responseCode = curl_getinfo($connection, CURLINFO_HTTP_CODE);
		curl_close($connection);
		
		if( $responseCode == 400 ) throw new DomainException('The video ID is invalid');
		if( $responseCode == 404 ) throw new DomainException('The video does not exist');
		if( $responseCode == 500 ) throw new DomainException('The provider experienced an error');
		if( $responseCode == 503 ) throw new DomainException('The provider is currently unavailable');

		if( isset($responseData['entry']['yt$noembed']) ) throw new DomainException('The video has embedding disabled');

		$this->title 	= $responseData['entry']['title']['$t'];
		$this->duration = $responseData['entry']['media$group']['yt$duration']['seconds'];
		$this->url 		= 'http://www.youtube.com/watch?v='.$queryString['v'];
	}

}