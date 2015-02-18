<?php

Route::group(['namespace' => 'Zeropingheroes\Lanager'], function()
{
	/*
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	*/
	Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'destroy']]);
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
	Route::resource('shouts', 'ShoutsController', ['only' => ['index', 'store', 'update', 'destroy']]);

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
	Route::get('playlists/{playlists}/play', ['as' => 'playlists.play', 'uses' => 'PlaylistsController@play']);

	/*
	|--------------------------------------------------------------------------
	| Achievements
	|--------------------------------------------------------------------------
	*/
	Route::resource('achievements', 'AchievementsController');

	/*
	|--------------------------------------------------------------------------
	| LANs
	|--------------------------------------------------------------------------
	*/
	Route::resource('lans', 'LansController');

	/*
	|--------------------------------------------------------------------------
	| REST API
	|--------------------------------------------------------------------------
	*/
	Route::group(['namespace' => 'Api\v1'], function()
	{
		Route::api(['version' => 'v1'], function () {
			Route::resource('achievements',			'AchievementsController',		['except' => ['create', 'edit'] ]);
			Route::resource('user-achievements',	'UserAchievementsController',	['except' => ['create', 'edit'] ]);
			Route::resource('events',				'EventsController',				['except' => ['create', 'edit'] ]);
			Route::resource('events.signups',		'EventSignupsController',		['except' => ['create', 'edit', 'update'] ]);
			Route::resource('event-types',			'EventTypesController',			['except' => ['create', 'edit'] ]);
			Route::resource('pages',				'PagesController',				['except' => ['create', 'edit'] ]);
			Route::resource('lans',					'LansController',				['except' => ['create', 'edit'] ]);
			Route::resource('playlists',			'PlaylistsController',			['except' => ['create', 'edit'] ]);
			Route::resource('playlists.items',		'PlaylistItemsController',		['except' => ['create', 'edit'] ]);
			Route::resource('playlists.items.votes','PlaylistItemVotesController',	['except' => ['create', 'edit', 'update'] ]);
			Route::resource('shouts',				'ShoutsController',				['except' => ['create', 'edit'] ]);
			Route::resource('roles',				'RolesController',				['except' => ['create', 'edit'] ]);
			Route::resource('user-roles',			'UserRolesController',			['except' => ['create', 'edit', 'update'] ]);
			Route::resource('users',				'UsersController',				['except' => ['create', 'store', 'edit', 'update'] ]);
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