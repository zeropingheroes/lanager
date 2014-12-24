<?php

Route::group(['namespace' => 'Zeropingheroes\Lanager'], function()
{
	/*
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	*/
	Route::resource('users', 'UsersController');
	Route::resource('user-roles', 'UserRolesController');
	Route::resource('user-achievements', 'UserAchievementsController');

	/*
	|--------------------------------------------------------------------------
	| Sessions
	|--------------------------------------------------------------------------
	*/
	Route::resource('sessions', 'SessionsController');
	Route::get('login', ['as' => 'sessions.login', 'uses' => 'SessionsController@create']);
	Route::get('logout', ['as' => 'sessions.logout', 'uses' => 'SessionsController@destroy']);

	/*
	|--------------------------------------------------------------------------
	| Pages
	|--------------------------------------------------------------------------
	*/
	Route::resource('pages', 'PagesController');

	/*
	|--------------------------------------------------------------------------
	| Shouts
	|--------------------------------------------------------------------------
	*/
	Route::resource('shouts', 'ShoutsController');

	/*
	|--------------------------------------------------------------------------
	| Usage
	|--------------------------------------------------------------------------
	*/
	Route::resource('usage', 'UsageController', ['only' => ['index', 'show']]);

	/*
	|--------------------------------------------------------------------------
	| Events
	|--------------------------------------------------------------------------
	*/
	Route::resource('events', 'EventsController');
	Route::resource('events.signups', 'EventSignupsController');

	/*
	|--------------------------------------------------------------------------
	| Playlists
	|--------------------------------------------------------------------------
	*/
	Route::resource('playlists', 'PlaylistsController');
	Route::resource('playlists.items', 'PlaylistItemsController');
	Route::resource('playlists.items.votes', 'PlaylistItemVotesController');

	/*
	|--------------------------------------------------------------------------
	| Achievements
	|--------------------------------------------------------------------------
	*/
	Route::resource('achievements', 'AchievementsController');

	/*
	|--------------------------------------------------------------------------
	| REST API
	|--------------------------------------------------------------------------
	*/
	Route::api(['version' => 'v1', 'protected' => true], function () {
		Route::resource('achievements', 'Api\v1\AchievementsController');
		Route::resource('events', 'Api\v1\EventsController');
		Route::resource('users', 'Api\v1\UsersController');
		Route::resource('pages', 'Api\v1\PagesController');
		Route::get('/test', ['as' => 'test'], function(){
			return;
		});		
	});
});

/*
|--------------------------------------------------------------------------
| Index
|--------------------------------------------------------------------------
*/
Route::get('/', function()
{
	return Redirect::to('shouts');
});