<?php namespace Zeropingheroes\Lanager\Validators;

use Config, Auth;
use Illuminate\Validation\Validator;

class PlaylistValidator extends Validator {

	/**
	 * Check if a given URL can be played by the playlist
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */
	public function validateCompatibleUrl($attribute, $value, $parameters)
	{
		// extract YouTube video ID from URL
		parse_str( parse_url( $value, PHP_URL_QUERY ), $youtubeUrl );

		// make sure the url was parsed
		if( ! array_key_exists( 'v', $youtubeUrl ) ) return false;
		
		// Verify that video ID is 11 chars long
		if(strlen($youtubeUrl['v']) != 11) return false;

		// Query YouTube API to verify video's existance
		$url = 'http://gdata.youtube.com/feeds/api/videos/'.$youtubeUrl['v'].'?format=5&alt=json';

		$ch = curl_init(); // get cURL handle

		// set cURL options
		curl_setopt_array($ch, array(
						CURLOPT_RETURNTRANSFER => true,	// do not output to browser
						CURLOPT_URL => $url,
						CURLOPT_NOBODY => true,			// do a HEAD request only
						CURLOPT_TIMEOUT => 5));			// set timeout 
		curl_exec($ch);
		$result = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200; // check if HTTP OK
		curl_close($ch);
		return $result;
	}

}