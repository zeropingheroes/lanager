<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class PlaylistItemValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'playlist_id'	=> 'required|exists:playlists,id',
		'user_id'		=> 'required|exists:users,id',
		'url'			=> 'required|url',
		'title'			=> 'required|max:255',
		'duration'		=> 'required|numeric|min:1',
		'playback_state'=> 'in:0,1,2',
		'skip_reason'	=> 'max:255',
	];
}