<?php namespace Zeropingheroes\Lanager\Models;

use Config, Auth;
use Illuminate\Support\MessageBag;
use Zeropingheroes\Lanager\Helpers\Duration;

class PlaylistItem extends BaseModel {

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

			// Perform extra validation (to be performed every create / update)
			if( $this->duration > Config::get('lanager/playlist.maxItemDuration') )
			{
				$maxDuration = new Duration( Config::get('lanager/playlist.maxItemDuration') );
				$errors->add('error', 'Item is too long. Please submit items ' . $maxDuration->shortFormat() . ' in length or less.' );
			}

			// Check if playlist full
			if( $duration = PlaylistItem::where('playback_state',0)->sum('duration') > Config::get('lanager/playlist.maxQueueLength') )
			{
				$errors->add('error', 'Playlist is currently full. Please try again later.');
			}
			
			// Check if video is duplicated
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
			
			// Check if user has submitted too many consecutive videos
			if( Config::get('lanager/playlist.maxConsecutiveItemsFromSingleUser') == 0)
			{
				$recentItemsToTake = 1;
			}
			else
			{
				$recentItemsToTake = Config::get('lanager/playlist.maxConsecutiveItemsFromSingleUser');
			}

			$recentSubmitters = PlaylistItem::where('playlist_id', $this->playlist_id)
				->orderby( 'created_at', 'desc' )
				->take( $recentItemsToTake )
				->lists('user_id');

			$distinctSubmitters = count(array_unique($recentSubmitters));

			// If only one person has submitted the last X videos AND that person is the logged in user
			if( $distinctSubmitters == 1 && $recentSubmitters[0] == Auth::user()->id )
			{
				if( Config::get('lanager/playlist.maxConsecutiveItemsFromSingleUser') == 0)
				{
					$errors->add('error', 'Consecutive video submissions from a single user have been disabled.' );
				}
				else
				{
					$errors->add('error', 'You have submitted the maximum number of consecutive items (' . Config::get('lanager/playlist.maxConsecutiveItemsFromSingleUser').')' );
				}
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

}