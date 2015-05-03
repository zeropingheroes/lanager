<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PlaylistValidator extends ValidatorAssistant {

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

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind the ID so it can be used in the validation rules
		if( isset($this->inputs['id']) ) $this->bind('id', $this->inputs['id']);
	}

}