<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class PlaylistValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'					=> 'required|max:255|unique:playlists,name,{id}',
		'description'			=> 'max:255',
		'playback_state'		=> 'in:0,1',
		'max_item_duration'		=> 'numeric|min:1',
		'user_skip_threshold'	=> 'numeric|min:1|max:100',
	];

}