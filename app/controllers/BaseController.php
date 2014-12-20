<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\ResourceServiceContract;
use Notification, Redirect;
use ReflectionClass;

class BaseController extends Controller implements ResourceServiceListenerContract {

	protected $resourceService;
	use ResourceControllerTrait;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
		if( !empty($this->resourceService) ) $this->resourceService = new $this->resourceService($this); // for child controllers
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
	public function storeSucceeded( ResourceServiceContract $resourceService )
	{
		Notification::success( $resourceService->messages );
		return Redirect::route( $resourceService->resourceName.'s.show', $resourceService->model->id); // TODO: use pluraliser
	}

	public function storeFailed( ResourceServiceContract $resourceService )
	{
		Notification::danger( $resourceService->errors );
		return Redirect::back()->withInput();
	}

	public function updateSucceeded( ResourceServiceContract $resourceService )
	{
		Notification::success( $resourceService->messages );
		return Redirect::route( $resourceService->resourceName.'s.show', $resourceService->model->id); // TODO: use pluraliser
	}

	public function updateFailed( ResourceServiceContract $resourceService )
	{
		Notification::danger( $resourceService->errors );
		return Redirect::back()->withInput();
	}

	public function destroySucceeded( ResourceServiceContract $resourceService )
	{
		Notification::success( $resourceService->messages );
		return Redirect::route( $resourceService->resourceName.'s.index' ); // TODO: use pluraliser
	}

	public function destroyFailed( ResourceServiceContract $resourceService )
	{
		Notification::danger( $resourceService->errors );
		return Redirect::back();
	}

}