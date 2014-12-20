<?php namespace Zeropingheroes\Lanager\Api;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait;

class BaseController extends Controller {

	use ControllerTrait;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
	}

}