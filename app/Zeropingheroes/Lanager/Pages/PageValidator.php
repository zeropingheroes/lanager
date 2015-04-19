<?php namespace Zeropingheroes\Lanager\Pages;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class PageValidator extends ValidatorAssistant {

	// Todo: check implementation
	// Not yet implemented
	// protected $filters = [
	// 	'parent_id' => 'intval',
	// ];

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'title'		=> 'required|max:255',
		'parent_id'	=> 'numeric|exists:pages,id',
		'position'	=> 'numeric|min:0',
		'published'	=> 'boolean',
	];

}