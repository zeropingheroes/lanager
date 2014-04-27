<?php namespace Zeropingheroes\Lanager\Models;

use Config;
use Illuminate\Support\MessageBag;
use Zeropingheroes\Lanager\Helpers\Duration;

class PlaylistItem extends BaseModel {

	public static $rules = array(
		'url'			=> 'required|url|compatible_url',
		'playlist_id'	=> 'numeric|exists:playlists,id'
	);

	public function beforeSave()
	{
		// Get title and duration data from YouTube
		parse_str( parse_url( $this->url, PHP_URL_QUERY ), $youtube_url );

		// Retrieve video metadata
		$response = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$youtube_url['v'].'?format=5&alt=json');
		$response = json_decode($response, true); // convert JSON response to array

		$this->title = $response['entry']['title']['$t'];
		$this->duration = $response['entry']['media$group']['yt$duration']['seconds'];
		$this->url = 'http://www.youtube.com/watch?v='.$youtube_url['v'];

		// Perform extra validation
		$errors = new MessageBag;
		if( $duration = PlaylistItem::where('playback_state',0)->sum('duration') > Config::get('lanager/playlist.maxQueueLength') )
		{
			$errors->add('error', 'Playlist is currently full. Please try again later.');
		}

		if( $this->duration > Config::get('lanager/playlist.maxItemDuration') )
		{
			$maxDuration = new Duration( Config::get('lanager/playlist.maxItemDuration') );
			$errors->add('error', 'Item is too long. Please submit items ' . $maxDuration->shortFormat() . ' in length or less.' );
		}

		if( $duplicates = PlaylistItem::where('url',$this->url)->count() > Config::get('lanager/playlist.maxDuplicates') )
		{
			if( Config::get('lanager/playlist.maxDuplicates') == 0)
			{
				$errors->add('error', 'Item has already been submitted to the playlist.' );
			}
			else
			{
				$errors->add('error', 'Item already has ' . $duplicates . ' occurences in the playlist.' );
			}
		}

		if( $errors->count() )
		{
			$this->validationErrors = $errors;
			return false;
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

}