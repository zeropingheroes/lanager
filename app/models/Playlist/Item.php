<?php namespace Zeropingheroes\Lanager\Models\Playlist;

use Zeropingheroes\Lanager\Models\BaseModel;
use Config, Auth;
use Illuminate\Support\MessageBag;
use Duration;

class Item extends BaseModel {

	protected $table = 'playlist_items';
	
	public static $rules = array(
		'url'			=> 'required|url',
		'playlist_id'	=> 'numeric|exists:playlists,id'
	);

	public function beforeSave()
	{
		if( $this->playback_state == 0 ) // only run validation if item is unplayed
		{
			$errors = new MessageBag;

			// extract YouTube video ID from URL
			parse_str( parse_url( $this->url, PHP_URL_QUERY ), $youtubeUrl );

			// make sure the url was parsed
			if( ! array_key_exists( 'v', $youtubeUrl ) )
			{
				$this->validationErrors = $errors->add('error', 'The given URL cannot be played by the playlist.' );
				return false;
			}

			// Verify that video ID is 11 chars long
			if(strlen($youtubeUrl['v']) != 11)
			{
				$this->validationErrors = $errors->add('error', 'The given URL cannot be played by the playlist.' );
				return false;
			}

			// Query YouTube API to verify video's existance
			$url = 'http://gdata.youtube.com/feeds/api/videos/'.$youtubeUrl['v'].'?format=5&alt=json';

			$ch = curl_init(); // get cURL handle

			// set cURL options
			curl_setopt_array($ch, array(
							CURLOPT_RETURNTRANSFER => true,	// do not output to browser
							CURLOPT_URL => $url,
							CURLOPT_FOLLOWLOCATION => true, // follow redirects
							CURLOPT_AUTOREFERER => true, // set referer on redirect
							CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
							CURLOPT_TIMEOUT => 5));			// set timeout 
			$response = curl_exec($ch);
			$result = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200; // check if HTTP OK
			curl_close($ch);
			if( ! $result )
			{
				$this->validationErrors = $errors->add('error', 'The given URL cannot be played by the playlist.' );
				return false;
			}

			// Get title and duration data from YouTube
			$response = json_decode($response, true); // convert JSON response to array

			$this->title = $response['entry']['title']['$t'];
			$this->duration = $response['entry']['media$group']['yt$duration']['seconds'];
			$this->url = 'http://www.youtube.com/watch?v='.$youtubeUrl['v'];

			// Check item is embeddable
			if(isset($response['entry']['yt$noembed']))
			{
				$errors->add('error', 'Item is not embeddable.' );
			}

			// Check item length
			if( $this->duration > $this->playlist->max_item_duration )
			{
				$errors->add('error', 'Item is too long. Please submit items under ' . Duration::longFormat($this->playlist->max_item_duration) . ' in duration.' );
			}

			// Check item duplication
			if( $occurrences = Item::where('url',$this->url)->where('playlist_id', $this->playlist_id)->count() > $this->playlist->max_duplicates )
			{
				$errors->add('error', 'Item already has ' . $occurrences . ' ' . str_plural('occurrence',$occurrences) . ' in the playlist - no more are permitted.' );
			}

			if( $errors->count() )
			{
				$this->validationErrors = $errors;
				return false;
			}
		}
	}

	public function playlist()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\Playlist');
	}

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
	}

	public function votes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Item\Vote', 'playlist_item_id');
	}

	public function scopeUnplayed($query)
	{
		return $query->where('playback_state', 0)->orderBy('created_at');
	}

}