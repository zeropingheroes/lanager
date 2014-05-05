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
Route::filter('checkResourcePermission', function($route, $request)
{
	$resources = explode('.', Route::currentRouteName());
	$action = array_pop($resources);

	$resource = '';
	$i = 0;

	foreach($resources as $resourceName)
	{
		$i++;

		// Collect resourceName item IDs
		$ids[$resourceName] = $route->parameter($resourceName);

		// Build expressive resource string
		if( (count($resources)) != $i )
		{
			$resource .= str_singular($resourceName).'.';
		}
		else
		{
			$resource .= $resourceName;
			$id = $route->parameter($resourceName);
		}
	}

	// print_r(array(
	// 	'action'	=> $action,
	// 	'resource'	=> $resource,
	// 	'id'		=> $id,
	// 	'resources'	=> $resources,
	// 	'ids'		=> $ids,
	// )); die();
	
	// Replace laravel-style route action names with their CRUD equivalents
	$actionsToReplace = array('store', 'show', 'index', 'edit', 'destroy');
	$replaceWithAction = array('create', 'read', 'read', 'update', 'delete');
	$action = str_replace($actionsToReplace, $replaceWithAction, $action);

	if( Authority::cannot($action, $resource, $id) )
	{
		return App::abort(403, 'You do not have permission to perform this request.');
	}
});

/*
|--------------------------------------------------------------------------
| Role-based Permissions
|--------------------------------------------------------------------------
|
| Checks if the logged in user has been assigned the specified role
|
*/
Route::filter('hasRole', function($route, $request, $value)
{
	$user = Authority::getCurrentUser();

	// If not logged in or user does not have role
	if( ! Auth::check() OR ! $user->hasRole($value) )
	{
		return App::abort(403, 'You must be assigned the role "'.$value.'" for this request');
	}
});