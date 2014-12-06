<?php namespace Zeropingheroes\Lanager;

use	Zeropingheroes\Lanager\Users\User;
use Illuminate\Support\MessageBag;
use LightOpenID;
use Auth, Input, Request, Redirect, View, UserImport, Notification;

class SessionsController extends BaseController {


	public function __construct()
	{
		$this->beforeFilter('permission',array('only' => array('index', 'store', 'edit', 'update')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ( Auth::check() ) return Redirect::to('/'); // if user is already logged in, redirect to home

		if ( Input::has('openid_identity') ) return $this->store(); // If request is an OpenID login, store the session

		return View::make('sessions.create')
						->with('title','Log In');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// If session is from a Steam OpenID
		if( str_contains( Input::get('openid_op_endpoint'), 'steamcommunity.com' ))
		{
			$openId = new LightOpenID(Request::server('HTTP_HOST'));
			if ( $openId->validate() )
			{
				$user = UserImport::fromSteam( substr($openId->identity, -17) );
				Auth::login($user);
				if( $user->steam_visibility != 3 ) return Redirect::route('users.show', $user->id);
				return Redirect::to('/');
			}
			Notification::danger('Unable to validate your OpenID');
			return Redirect::route( 'sessions.login' );
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id = '')
	{
		if( ! $id )
		{
			Auth::logout();
			return Redirect::to('/');
		}
	}

}
