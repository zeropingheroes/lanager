<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\BaseModel;
use Redirect, Request, Event, Route, Response, Notification;

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @param  BaseModel 	$model 			The model to be processed
	 * @param  str 			$successRoute 	The full route name to redirect to when processing completes without error
	 * @param  str 			$failureRoute 	The full route name to redirect to when errors are found
	 * @return void
	 */
	protected function process( BaseModel $model, $successRoute = '', $failureRoute = '', $extraRouteParameters = '' )
	{
		// Collect route and model information
		$route['action'] = substr( Route::currentRouteName(), strrpos( Route::currentRouteName(), '.' )+1 );
		$route['resource'] = str_replace('.' . $route['action'], '', Route::currentRouteName());
		$route['parameters'] = Route::current()->parameters();
		$route['parameters'] = is_array($extraRouteParameters) ? array_merge($route['parameters'], $extraRouteParameters) : $route['parameters']; 
		
		$reflection = new \ReflectionClass($model);
		$modelName = strtolower($reflection->getShortName());

		// Set up redirects
		if( empty($failureRoute) )
		{
			switch($route['action'])
			{
				case 'store':
					$failureRoute = $route['resource'] . '.create';
					break;
				case 'update':
					$failureRoute = $route['resource'] . '.edit';
					break;
				case 'destroy':
					$failureRoute = $route['resource'] . '.show';
					break;
			}
		}
		if( empty($successRoute) )
		{
			switch($route['action'])
			{
				case 'store':
					$successRoute = $route['resource'] . '.show';
					break;
				case 'update':
					$successRoute = $route['resource'] . '.show';
					break;
				case 'destroy':
					$successRoute = $route['resource'] . '.index';
					break;
			}
		}

		// Attempt to peform requested action (perform validation in model)
		if( $route['action'] == 'destroy')
		{
			if( ! $model->delete() )
			{
				if ( Request::ajax() ) return Response::json($model->errors(), 400);
				Notification::danger($model->errors()->all());
				return Redirect::route( $failureRoute, $route['parameters'] );
			}
		
			if ( Request::ajax() ) return Response::json( $model, 204);
			// Remove the model we just destroyed from the route parameters
			unset($route['parameters'][str_plural($modelName)]);
			$successParticiple = 'destroyed';
		}
		else // Storing or updating
		{
			if( ! $model->save() )
			{
				if ( Request::ajax() ) return Response::json($model->errors(), 400);
				Notification::danger($model->errors()->all());
				return Redirect::route( $failureRoute, $route['parameters'] );
			}

			if( $route['action'] == 'store' )
			{
				if( Request::ajax() ) return Response::json($model, 201);
				$successParticiple = 'created';
			}
			else
			{
				if( Request::ajax() ) return Response::json($model, 200);
				$successParticiple = 'updated';
			}

			// Add the model we just inserted / updated into the route parameters
			$route['parameters'][str_plural($modelName)] = $model->id;
		}

		Event::fire( 'lanager.' . $route['resource'] . '.' . $route['action'], $model );
		Notification::success('Successfully '. $successParticiple . ' ' .$modelName);
		return Redirect::route($successRoute, $route['parameters'] );
	}

}