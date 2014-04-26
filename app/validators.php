<?php

Validator::resolver(function($translator, $data, $rules, $messages)
{
	$messages = Lang::get( 'validation.custom' ); 
	return new Zeropingheroes\Lanager\Validators\CustomValidator($translator, $data, $rules, $messages);
});

Validator::resolver(function($translator, $data, $rules, $messages)
{
	$messages = Lang::get( 'validation.playlist' ); 
	return new Zeropingheroes\Lanager\Validators\PlaylistValidator($translator, $data, $rules, $messages);
});
