<?php

Validator::resolver(function($translator, $data, $rules, $messages)
{
	$messages = Lang::get( 'validation.custom' ); 
	return new Zeropingheroes\Lanager\Validators\CustomValidator($translator, $data, $rules, $messages);
});
