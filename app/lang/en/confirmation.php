<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Confirmation Language lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are used by the controllers and services
	| when a user should get confirmation of an operation.
	|
	*/

	'before' => [
		'resource' => [
			'destroy' => 'Are you sure you want to delete this :resource?',
		],
	],
	'after' => [
		'resource' => [
			'store' => 'Successfully created :resource',
			'update' => 'Successfully updated :resource',
			'destroy' => 'Successfully deleted :resource',
		],
	],

);