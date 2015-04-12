<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PlaylistItemVoteValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'playlist_item_id'	=> 'required|exists:playlist_items,id',
		'user_id'			=> 'required|exists:users,id|unique:playlist_item_votes,user_id,NULL,id,playlist_item_id,{playlist_item_id}',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'user_id.unique'	=> 'You have already voted to skip this item',
	];

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind the ID so it can be used in the validation rules
		if( isset($this->inputs['playlist_item_id']) ) $this->bind('playlist_item_id', $this->inputs['playlist_item_id']);
	}
}