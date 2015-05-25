<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\Shouts\ShoutService;
use View;
use Redirect;

class ShoutsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new ShoutService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$shouts = $this->service->all();

		return View::make( 'shouts.index' )
					->with( 'title', 'Shouts' )
					->with( 'shouts', $shouts );
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('shouts.index' );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return $this->redirectAfterStore();
	}
}