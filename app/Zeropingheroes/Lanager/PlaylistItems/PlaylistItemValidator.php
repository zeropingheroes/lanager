<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Playlists\Playlist;
use Zeropingheroes\Lanager\PlaylistItems\PlaylistItem;
use Duration, Carbon\Carbon;;

class PlaylistItemValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'playlist_id'	=> 'required|exists:playlists,id',
		'user_id'		=> 'required|exists:users,id',
		'url'			=> 'required|url|unique:playlist_items,url,{id},id,playlist_id,{playlist_id}',
		'title'			=> 'required|max:255',
		'duration'		=> 'required|numeric|min:1|max:{duration.max}',
		'playback_state'=> 'in:0,1,2',
		'played_at'		=> 'date_format:Y-m-d H:i:s',
		'skip_reason'	=> 'max:255',
	];

	/**
	 * Validation rules to only when storing the item
	 * @var array
	 */
	protected $rulesStore = [
		'playlist_id'	=> 'submissionsPerHour:2',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'url.unique'	=> 'The item has already been added to this playlist',
		'submissions_per_hour'	=> 'You have submitted 2 items in the past hour - the maximum allowed. Please wait a while and try again later.',
	];

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind item id and playlist id for use in rules
		if( isset($this->inputs['id']) ) $this->bind('id', $this->inputs['id']);
		$this->bind('playlist_id', $this->inputs['playlist_id']);
	
		// Bind the maximum permitted item duration for use in rules
		$playlist = Playlist::findOrFail( $this->inputs['playlist_id'] );
		$this->bind('duration.max', $playlist->max_item_duration);
		$this->messages['duration.max'] = 'The item\'s duration must not exceed ' . Duration::longFormat($playlist->max_item_duration);
	}

	/**
	 * Validate a user has not submitted over the permitted number of submissions per hour
	 * @param  string $attribute  Name of the input field
	 * @param  string $value      Value of the input field
	 * @param  array  $parameters
	 * @return bool               True if validation passes, false otherwise
	 */
	public function customSubmissionsPerHour($attribute, $value, $parameters)
	{
		$oneHourAgo = (new Carbon)->subSeconds(3600);

		$submittedInLastHour = PlaylistItem::where( 'playlist_id', $this->inputs['playlist_id'] )
											->where( 'user_id', $this->inputs['user_id'] )
											->where('created_at','>',$oneHourAgo)
											->count();
		return $submittedInLastHour < $parameters[0];
	}

}