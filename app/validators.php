<?php
/*
|--------------------------------------------------------------------------
| Custom Validators
|--------------------------------------------------------------------------
|
| Here is where you can resolve custom validator classes
|
*/

Validator::resolver(function($translator, $data, $rules, $messages)
{
	$messages = Lang::get( 'validation.custom' ); 
	return new Zeropingheroes\Lanager\Validators\CustomValidator($translator, $data, $rules, $messages);
});
