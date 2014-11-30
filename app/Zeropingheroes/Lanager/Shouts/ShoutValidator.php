<?php namespace Zeropingheroes\Lanager\Shouts;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Auth, Carbon\Carbon;

class ShoutValidator extends ValidatorAssistant {

	protected $rules = [
		'user_id'		=> 'required|exists:users,id',
		'content'		=> 'required|max:140',
		'pinned'		=> 'boolean',
	];

	protected $rulesStore = [
		'content' => 'flood_protect:shouts',
	];

	protected $messages = [
		'content.flood_protect'	=> 'You have posted too recently - please wait a while and try again.',
	];

	public function customFloodProtect($attribute, $value, $parameters)
	{
		$date = new Carbon;
		$date->subSeconds(15);
		return ! Auth::user()->{$parameters[0]}()->where('created_at','>',$date)->count();
	}

}