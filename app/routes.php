<?php

Route::group(['namespace' => 'Zeropingheroes\Lanager'], function()
{
	/*
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	*/
	Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'destroy']]);
	Route::resource('user-roles', 'UserRolesController', ['only' => ['index', 'create', 'store', 'destroy']]);
	Route::resource('user-achievements', 'UserAchievementsController', ['only' => ['index', 'create', 'edit', 'store', 'update', 'destroy']]);

	/*
	|--------------------------------------------------------------------------
	| Sessions
	|--------------------------------------------------------------------------
	*/
	Route::resource('sessions', 'SessionsController');
	Route::get('login', ['as' => 'sessions.create', 'uses' => 'SessionsController@create']);
	Route::get('logout', ['as' => 'sessions.destroy', 'uses' => 'SessionsController@destroy']);

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
	| Application Usage
	|--------------------------------------------------------------------------
	*/
	Route::resource('application-usage', 'ApplicationUsageController', ['only' => ['index']]);

	/*
	|--------------------------------------------------------------------------
	| Events
	|--------------------------------------------------------------------------
	*/
	Route::resource('events', 'EventsController');
	Route::resource('events.signups', 'EventSignupsController');
	Route::resource('event-types', 'EventTypesController', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);

	/*
	|--------------------------------------------------------------------------
	| Playlists
	|--------------------------------------------------------------------------
	*/
	Route::resource('playlists', 'PlaylistsController');
	Route::resource('playlists.items', 'PlaylistItemsController', ['only' => ['index', 'store', 'update', 'destroy']]);
	Route::resource('playlists.items.votes', 'PlaylistItemVotesController', ['only' => ['store', 'destroy']]);
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
	| Roles
	|--------------------------------------------------------------------------
	*/
	Route::resource('roles', 'RolesController');

	/*
	|--------------------------------------------------------------------------
	| Logs
	|--------------------------------------------------------------------------
	*/
	Route::resource('logs', 'LogsController', ['only' => ['index']]);

	/*
	|--------------------------------------------------------------------------
	| Dashboard
	|--------------------------------------------------------------------------
	*/
	Route::get('dashboard', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

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
			Route::resource('application-usage',	'ApplicationUsageController',	['only' => ['index'] ]);
			Route::resource('logs',					'LogsController',				['only' => ['index'] ]);
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