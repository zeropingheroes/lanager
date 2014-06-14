<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\Models\BaseModel;
use Redirect, Request, Event;

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
	 * @param  str 			$action 	The action being carried out
	 * @param  str 			$resource 	The resource
	 * @param  BaseModel 	$model 		The model to be processed
	 * @return void
	 */
	protected function process( $action, $resource, BaseModel $model  )
	{
		switch($action)
		{
			case 'store':
				$redirect['errors'] = str_plural($resource) . '.create';
				break;
			case 'update':
				$redirect['errors'] = str_plural($resource) . '.edit';
				break;
		}

		$redirect['success'] = str_plural($resource) . '.show';

		if( ! $model->save() )
		{
			if ( Request::ajax() ) return Response::json($model->errors(), 400);

			return Redirect::route( $redirect['errors'], array(str_singular($resource) => $model->id) )->withErrors($model->errors());
		}

		Event::fire( 'lanager.' . str_singular($resource) . '.' . $action );

		if ( Request::ajax() ) return Response::json($model, 201);

		return Redirect::route( $redirect['success'], array(str_singular($resource) => $model->id) );

	}

}