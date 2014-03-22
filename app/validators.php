<?php

Validator::resolver(function($translator, $data, $rules, $messages)
{
	return new Zeropingheroes\Lanager\Validators\CustomValidator($translator, $data, $rules, $messages);
});
