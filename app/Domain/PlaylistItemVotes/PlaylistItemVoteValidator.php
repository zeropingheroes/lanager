<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItemVotes;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class PlaylistItemVoteValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'playlist_item_id'	=> 'required|exists:playlist_items,id',
		'user_id'			=> 'required|exists:users,id',
	];
}