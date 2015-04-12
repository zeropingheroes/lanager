<?php namespace Zeropingheroes\Lanager\Shouts;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Auth, Carbon\Carbon;

class ShoutValidator extends ValidatorAssistant {

	protected $preserveScopeValues = true; // merge scoped rules

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'user_id'		=> 'required|exists:users,id',
		'content'		=> 'required|max:140',
		'pinned'		=> 'boolean',
	];

	/**
	 * Validation rules to only when storing the item
	 * @var array
	 */
	protected $rulesStore = [
		'content'		=> 'flood_protect:shouts',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'content.flood_protect'	=> 'You have posted too recently - please wait a while and try again.',
		'pinned.boolean'		=> 'Pinned must be set to either true or false',
	];

	/**
	 * Validate a user has not posted a shout in the last X seconds
	 * @param  string $attribute  Name of the input field
	 * @param  string $value      Value of the input field
	 * @param  array  $parameters
	 * @return bool               True if validation passes, false otherwise
	 */
	public function customFloodProtect($attribute, $value, $parameters)
	{
		$date = new Carbon;
		$date->subSeconds(15); // todo: move to parameter
		return ! Auth::user()->{$parameters[0]}()->where('created_at','>',$date)->count();
	}

}