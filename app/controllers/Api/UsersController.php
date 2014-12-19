<?php namespace Zeropingheroes\Lanager\Api;

use Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\Users\UserTransformer;

class UsersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$users = User::visible()->orderBy('username', 'asc')->get();
		return $this->response->collection($users, new UserTransformer);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::visible()->findOrFail($id);

		return $this->response->item($user, new UserTransformer);
	}

}