<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\BaseModel;
use Notification;
use ReflectionClass;

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

	protected function save( BaseModel $model )
	{
		$resource = $this->resourceName($model);
		$action = isset($model->id) ? 'update' : 'store';

		$validator = new $model->validator( $model->toArray() );

		if ( $validator->fails() )
		{
			Notification::danger( $validator->errors()->all() );
			return false;
		}
		else
		{
			$model->save();
			Notification::success( trans('confirmation.after.resource.' . $action, ['resource' => trans('resources.' . $resource) ]) );
			return true;			
		}
	}

	protected function delete( BaseModel $model )
	{
		$resource = $this->resourceName($model);
		$delete = $model->delete();
		if( $delete ) Notification::success( trans('confirmation.after.resource.destroy', ['resource' => trans('resources.' . $resource) ]) );
		return $delete;
	}

	private function resourceName( BaseModel $model )
	{
		$modelClass = (new ReflectionClass($model))->getShortName();
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $modelClass)); // camel to dash
	}

}