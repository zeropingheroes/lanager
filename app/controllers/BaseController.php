<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\Models\BaseModel;
use Redirect, Request, Event, Route, Response;

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
	protected function process( BaseModel $model, $successRoute = '', $failureRoute = '' )
	{
		// Collect route and model information
		$route['action'] = substr( Route::currentRouteName(), strrpos( Route::currentRouteName(), '.' )+1 );
		$route['resource'] = str_replace('.' . $route['action'], '', Route::currentRouteName());
		$route['parameters'] = Route::current()->parameters();
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

		if( $route['action'] == 'destroy')
		{
			// Attempt to destroy model
			if( ! $model->destroy($model->id) )
			{
				if ( Request::ajax() ) return Response::json($model->errors(), 400);
				return Redirect::route( $failureRoute, $route['parameters'] )->withErrors($model->errors());
			}
		
			if ( Request::ajax() ) return Response::json( $model, 204);
			// Remove the model we just destroyed from the route parameters
			unset($route['parameters'][str_plural($modelName)]);
			return Redirect::route( $successRoute, $route['parameters'] );
		}
		else // Storing or updating
		{
			// Attempt to save model
			if( ! $model->save() )
			{
				if ( Request::ajax() ) return Response::json($model->errors(), 400);
				return Redirect::route( $failureRoute, $route['parameters'] )->withErrors($model->errors());
			}
			if ( Request::ajax() ) return Response::json($model, 201);

			// Add the model we just inserted / updated into the route parameters
			$route['parameters'][$modelName] = $model->id;
			return Redirect::route( $successRoute, $route['parameters'] );
		}

		Event::fire( 'lanager.' . $route['resource'] . '.' . $route['action'], $model );
	}

}