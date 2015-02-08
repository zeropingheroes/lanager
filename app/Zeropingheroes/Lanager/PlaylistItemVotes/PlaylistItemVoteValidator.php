<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PlaylistItemVoteValidator extends ValidatorAssistant {

	protected $rules = [
		'playlist_item_id'	=> 'required|exists:playlist_items,id',
		'user_id'			=> 'required|exists:users,id|unique:playlist_item_votes,user_id,NULL,id,playlist_item_id,{playlist_item_id}',
	];

	protected $messages = [
		'user_id.unique'	=> 'You have already voted to skip this item',
	];

	protected function before()
	{
		$this->bind('playlist_item_id', $this->inputs['playlist_item_id']);
	}
}