<?php namespace Zeropingheroes\Lanager\InfoPages;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class InfoPageValidator extends ValidatorAssistant {

	protected $rules = [
		'title'		=> 'required|max:255',
		'parent_id'	=> 'numeric|exists:info_pages,id',
	];

}