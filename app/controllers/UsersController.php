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
		$options['visible'] = true;
		$eagerLoad =
		[
			'userAchievements',
			'roles',
			'state.application',
			'state.server'
		];

		$users = $this->service->all($options, $eagerLoad);

		return View::make('users.index')
					->with('title','Users')
					->with('users', $users);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$eagerLoad = ['userAchievements', 'roles', 'shouts', 'state.application', 'state.server'];
		$user = $this->service->single( $id, $eagerLoad );

		return View::make('users.show')
					->with('title',$user->username)
					->with('user',$user);
	}

}