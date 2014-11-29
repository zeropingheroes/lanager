<?php namespace Zeropingheroes\Lanager\Playlists\Items;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Playlists\Playlist;
use Duration;

class ItemValidator extends ValidatorAssistant {

	protected $rules = [
		'playlist_id'	=> 'required|exists:playlists,id',
		'user_id'		=> 'required|exists:users,id',
		'url'			=> 'required|url',
		'title'			=> 'required|max:255',
		'duration'		=> 'required|numeric|min:1',
		'playback_state'=> 'in:0,1,2',
	];

	protected $messages = [
		'url.unique'	=> 'The item has already been added to this playlist',
	];

	protected function before()
	{
		// Inject the given playlist id into the unique rule
		$this->rules['url'] .= '|unique:playlist_items,url,NULL,id,playlist_id,' . $this->inputs['playlist_id'];
		
		// Inject the maximum permitted item duration
		$playlist = Playlist::findOrFail( $this->inputs['playlist_id'] );
		$this->rules['duration'] .= '|max:' . $playlist->max_item_duration;
		$this->messages['duration.max'] = 'The item\'s duration must not exceed ' . Duration::longFormat($playlist->max_item_duration);

	}
}