<?php namespace Zeropingheroes\Lanager\Validators;

use Config, Auth;
use ExpressiveDate;
use Carbon\Carbon;

class CustomValidator extends \Illuminate\Validation\Validator {

	/**
	 * Check to see if user has submitted an item more recently than the flood protect time
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */	
	public function validateFloodProtect($attribute, $value, $parameters)
	{
		$date = new ExpressiveDate;
		$date->minusSeconds(Config::get('lanager/floodprotect'.$parameters[0]));
		return ! Auth::user()->{$parameters[0]}()->where('created_at','>',$date)->count();
	}

	/**
	 * Check to see if end date comes after start date
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */
	public function validateDateNotBeforeThisInput($attribute, $value, $parameters)
	{
		if( !empty($value) )
		{
			$start = $this->getValue($parameters[0]); // get the value of the parameter (start)

			$start = Carbon::createFromFormat('d/m/Y H:i',$start)->timestamp;
			$end = Carbon::createFromFormat('d/m/Y H:i',$value)->timestamp;

			return ($end > $start);
		}
		return false;
	}

	protected function replaceDateNotBeforeThisInput($message, $attribute, $rule, $parameters)
	{
		return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
	}

	/**
	 * Check if a given URL can be played by the playlist
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */
	public function validatePlaylistCompatibleUrl($attribute, $value, $parameters)
	{
		// extract YouTube video ID from URL
		parse_str( parse_url( $value, PHP_URL_QUERY ), $youtubeUrl );
		
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