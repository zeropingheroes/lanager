<?php namespace Zeropingheroes\Lanager\Users;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class UserValidator extends ValidatorAssistant {

	protected $rules = [
		'username'			=> 'required|max:32',
		'steam_id_64'		=> 'required|max:17',
		'steam_visibility'	=> 'required|in:0,1,2,3',
		'ip'				=> 'ip',
		'avatar'			=> 'url',
		'visible'			=> 'boolean',
	];

}