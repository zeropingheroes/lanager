<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class PageValidator extends ValidatorAssistant implements InputValidatorContract {

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