<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Users\User;
use Redirect, View;

class UsersController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter( 'permission', ['only' => ['destroy'] ] );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::visible()->orderBy('username', 'asc');

		return View::make('users.index')
					->with('title','Users')
					->with('users',$users->paginate(10));
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

		return View::make('users.show')
					->with('title',$user->username)
					->with('user',$user);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::visible()->findOrFail($id);
		$this->delete($user);
		return Redirect::route('users.index');
	}

}