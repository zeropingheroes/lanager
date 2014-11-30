<?php namespace Zeropingheroes\Lanager\Shouts;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Auth, Carbon\Carbon;

class ShoutValidator extends ValidatorAssistant {

	protected $rules = [
		'username'			=> 'required|max:32',
		'steam_id_64'		=> 'required|max:17|unique:users',
		'steam_visibility'	=> 'required|in:0,1,2,3',
		'ip'				=> 'ip',
		'avatar'			=> 'url',
		'visible'			=> 'boolean',
	];

}