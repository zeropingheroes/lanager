<?php namespace Zeropingheroes\Lanager\Playlists;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PlaylistValidator extends ValidatorAssistant {

	protected $rules = [
		'name'					=> 'required|max:255|unique:playlists,name,{id}',
		'description'			=> 'max:255',
		'max_item_duration'		=> 'numeric|min:1',
		'user_skip_threshold'	=> 'numeric|min:1|max:100',
	];

	protected function before()
	{
		if( isset($this->inputs['id']) ) $this->bind('id', $this->inputs['id']);
	}

}