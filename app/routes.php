<?php

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
*/

Route::pattern('user', '[0-9]+');
Route::pattern('shout', '[0-9]+');
Route::pattern('event', '[0-9]+');
Route::pattern('playlist', '[0-9]+');
Route::pattern('playlistItem', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::get(
	'users/openidlogin',
	array(
		'as' => 'users.openIdLogin',
		'uses' => 'Zeropingheroes\Lanager\UsersController@openIdLogin')
);

Route::get(
	'users/logout',
	array(
		'as' => 'users.logout',
		'uses' => 'Zeropingheroes\Lanager\UsersController@logout')
);

Route::group(array('before' => 'hasRole:SuperAdmin'), function()
{
	Route::get(
		'users/{user}/roles',
		array(
			'as' => 'users.roles.edit',
			'uses' => 'Zeropingheroes\Lanager\UsersController@editRoles')
	);
	Route::put(
		'users/{user}/roles',
		array(
			'as' => 'users.roles.update',
			'uses' => 'Zeropingheroes\Lanager\UsersController@updateRoles')
	);
});
Route::resource('users', 'Zeropingheroes\Lanager\UsersController');

/*
|--------------------------------------------------------------------------
| Info Pages
|--------------------------------------------------------------------------
*/
Route::resource('infopages', 'Zeropingheroes\Lanager\InfoPagesController');

/*
|--------------------------------------------------------------------------
| Shouts
|--------------------------------------------------------------------------
*/
Route::get(
	'shouts/{shout}/pin',
	array(
		'as' => 'shouts.pin',
		'uses' => 'Zeropingheroes\Lanager\ShoutsController@pin')
);
Route::resource('shouts', 'Zeropingheroes\Lanager\ShoutsController');

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/
Route::get(
	'statistics/applications/current-usage',
	array(
		'as' => 'statistics.applications.current-usage',
		'uses' => 'Zeropingheroes\Lanager\StatesController@currentApplicationUsage')
);
Route::get(
	'statistics/servers/current-usage',
	array(
		'as' => 'statistics.servers.current-usage',
		'uses' => 'Zeropingheroes\Lanager\StatesController@currentServerUsage')
);

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/
Route::get('events/timetable', 
	array(
		'as' => 'events.timetable',
		'uses' => 'Zeropingheroes\Lanager\EventsController@timetable')
);
Route::get(
	'events/{event}/join',
	array(
		'as' => 'events.join',
		'uses' => 'Zeropingheroes\Lanager\EventsController@join')
);
Route::get(
	'events/{event}/leave',
	array(
		'as' => 'events.leave',
		'uses' => 'Zeropingheroes\Lanager\EventsController@leave')
);
Route::resource('events', 'Zeropingheroes\Lanager\EventsController');

/*
|--------------------------------------------------------------------------
| Playlists
|--------------------------------------------------------------------------
*/
Route::resource('playlists', 'Zeropingheroes\Lanager\PlaylistsController');
Route::resource('playlists.items', 'Zeropingheroes\Lanager\Playlist\ItemsController');


/*
|--------------------------------------------------------------------------
| Index
|--------------------------------------------------------------------------
*/
Route::get('/', function()
{
	return Redirect::to('shouts');
});