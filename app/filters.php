<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	if ( Config::get('lanager/config.installed') !== true )
	{
		return 'Run <pre>php artisan lanager:install</pre> from the lanager/ directory before continuing';
	}
});


App::after(function($request, $response)
{
	//
});


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| Resource-based Permissions
|--------------------------------------------------------------------------
|
| Checks if the logged in user can perform the requested action on the
| requested resource item.
| Gets resource type (e.g. User) action (e.g. delete) and item id from request.
|
*/
Route::filter('permission', function($route, $request)
{
	$currentRouteName = str_replace('api.', '', Route::currentRouteName()); // remove "api" prefix from route names

	$routeArray = explode('.', $currentRouteName);
	
	$action		= array_pop($routeArray);
	$id 		= $route->parameter(end((array_values($routeArray))));
	$resource 	= implode('.', $routeArray);

	if( Authority::cannot($action, $resource, $id) ) return App::abort(403);

});