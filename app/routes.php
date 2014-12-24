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
	Route::group(['namespace' => 'Api\v1'], function()
	{
		Route::api(['version' => 'v1'], function () {
			Route::resource('achievements',			'AchievementsController',	['except' => ['create', 'edit'] ]);
			Route::resource('events',				'EventsController',			['except' => ['create', 'edit'] ]);
			Route::resource('pages',				'PagesController',			['except' => ['create', 'edit'] ]);
			Route::resource('users',				'UsersController',			['except' => ['create', 'edit'] ]);
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