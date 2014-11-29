<?php namespace Zeropingheroes\Lanager\Playlists\Items;

class YouTubeVideo extends PlayableItem {

	const API_URL = 'http://gdata.youtube.com/feeds/api/videos/';
	const API_FORMAT_QUERY = '?format=5&alt=json';

	public function __construct( $url )
	{
		parse_str( parse_url( $url, PHP_URL_QUERY ), $queryString);
		
		if( empty($queryString['v']) ) throw new UnplayableItemException('The video ID is invalid');

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
		
		if( $responseCode == 400 ) throw new UnplayableItemException('The video ID is invalid');
		if( $responseCode == 404 ) throw new UnplayableItemException('The video does not exist');
		if( $responseCode == 500 ) throw new UnplayableItemException('The provider experienced an error');
		if( $responseCode == 503 ) throw new UnplayableItemException('The provider is currently unavailable');

		if( isset($responseData['entry']['yt$noembed']) ) throw new UnplayableItemException('The video has embedding disabled');

		$this->title 	= $responseData['entry']['title']['$t'];
		$this->duration = $responseData['entry']['media$group']['yt$duration']['seconds'];
		$this->url 		= 'http://www.youtube.com/watch?v='.$queryString['v'];

	}
}