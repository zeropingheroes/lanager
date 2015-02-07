<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceControllerTrait;
use Notification, Redirect;
use ReflectionClass;

class BaseController extends Controller implements ResourceServiceListenerContract {

	protected $service;
	use ResourceControllerTrait;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
	}

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
		$validator->scope([$action]);

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

	/*
	|--------------------------------------------------------------------------
	| Default Controller Listener Methods
	|--------------------------------------------------------------------------
	|
	| These methods provide sensible boilerplate defaults for after-success and
	| after-failure actions when the app is being accessed via a web browser. 
	| These methods can be overridden by child controllers if needed.
	|
	*/
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->model()->id );
	}

	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->model()->id );
	}

	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}

	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back();
	}

}