<?php namespace Zeropingheroes\Lanager\Pages;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PageValidator extends ValidatorAssistant {

	// Not yet implemented
	// protected $filters = [
	// 	'parent_id' => 'intval',
	// ];

	protected $rules = [
		'title'		=> 'required|max:255',
		'parent_id'	=> 'numeric|exists:pages,id',
	];

}