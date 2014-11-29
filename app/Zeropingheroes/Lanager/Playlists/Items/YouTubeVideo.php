<?php namespace Zeropingheroes\Lanager\Playlists\Items;

class YouTubeVideo extends PlayableItem {

	const API_URL = 'http://gdata.youtube.com/feeds/api/videos/';
	const API_FORMAT_QUERY = '?format=5&alt=json';

	public function __construct( $url )
	{
		parse_str( parse_url( $url, PHP_URL_QUERY ), $queryString);
		
		if( empty($queryString['v']) ) throw new UnplayableItemException('Invalid video ID');

		$videoApiUrl = self::API_URL.$queryString['v'].self::API_FORMAT_QUERY;

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
		
		if( $responseCode == 404 ) throw new UnplayableItemException('Video does not exist');
		if( $responseCode == 400 ) throw new UnplayableItemException('Invalid video ID');

		if( isset($responseData['entry']['yt$noembed']) ) throw new UnplayableItemException('Video owner does not allow embedding');

		$this->title 	= $responseData['entry']['title']['$t'];
		$this->duration = $responseData['entry']['media$group']['yt$duration']['seconds'];
		$this->url 		= 'http://www.youtube.com/watch?v='.$queryString['v'];

	}
}