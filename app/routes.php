<?php

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
*/

Route::pattern('event', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::resource('users', 'Zeropingheroes\Lanager\UsersController');
Route::resource('role-assignments', 'Zeropingheroes\Lanager\RoleAssignmentsController');

/*
|--------------------------------------------------------------------------
| Sessions
|--------------------------------------------------------------------------
*/
Route::resource('sessions', 'Zeropingheroes\Lanager\SessionsController');
Route::get('login', array('as' => 'sessions.login', 'uses' => 'Zeropingheroes\Lanager\SessionsController@create'));
Route::get('logout', array('as' => 'sessions.logout', 'uses' => 'Zeropingheroes\Lanager\SessionsController@destroy'));

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
Route::get('events/timetable', array('as' => 'events.timetable', 'uses' => 'Zeropingheroes\Lanager\EventsController@timetable'));
Route::resource('events', 'Zeropingheroes\Lanager\EventsController');
Route::resource('signups', 'Zeropingheroes\Lanager\SignupsController');

/*
|--------------------------------------------------------------------------
| Playlists
|--------------------------------------------------------------------------
*/
Route::resource('playlists', 'Zeropingheroes\Lanager\PlaylistsController');
Route::resource('playlists.items', 'Zeropingheroes\Lanager\Playlists\ItemsController');
Route::resource('playlists.items.votes', 'Zeropingheroes\Lanager\Playlists\Items\VotesController');

/*
|--------------------------------------------------------------------------
| Achievements
|--------------------------------------------------------------------------
*/
Route::resource('achievements', 'Zeropingheroes\Lanager\AchievementsController');
Route::resource('awards', 'Zeropingheroes\Lanager\AwardsController');

/*
|--------------------------------------------------------------------------
| Index
|--------------------------------------------------------------------------
*/
Route::get('/', function()
{
	return Redirect::to('shouts');
});