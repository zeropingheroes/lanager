<?php namespace Zeropingheroes\Lanager\Models;

use Config, Auth;
use Illuminate\Support\MessageBag;
use Zeropingheroes\Lanager\Helpers\Duration;

class PlaylistItem extends BaseModel {

	public static $rules = array(
		'url'			=> 'required|url|compatible_url',
		'playlist_id'	=> 'numeric|exists:playlists,id'
	);

	public function beforeCreate()
	{
		// Perform extra one-time validation (to be performed only on update)
		$errors = new MessageBag;
		
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

		// Perform extra validation (to be performed every create / update)
		$errors = new MessageBag;
		if( $this->duration > Config::get('lanager/playlist.maxItemDuration') )
		{
			$maxDuration = new Duration( Config::get('lanager/playlist.maxItemDuration') );
			$errors->add('error', 'Item is too long. Please submit items ' . $maxDuration->shortFormat() . ' in length or less.' );
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