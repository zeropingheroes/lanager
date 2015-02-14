<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Users\UserService;
use View;

class UsersController extends BaseController {

	protected $route = 'users';

	public function __construct()
	{
		parent::__construct();
		$this->service = new UserService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$options['orderBy'] = ['username'];
		return View::make('users.index')
					->with('title','Users')
					->with('users', $this->service->all($options));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = $this->service->single($id);
		return View::make('users.show')
					->with('title',$user->username)
					->with('user',$user);
	}

}