<?php namespace Zeropingheroes\Lanager;

use	Zeropingheroes\Lanager\Models\User,
	Zeropingheroes\Lanager\Interfaces\SteamUserRepositoryInterface;
use Illuminate\Support\MessageBag;
use LightOpenID;
use Auth, Input, Request, Redirect, View, UserImport;

class SessionsController extends BaseController {


	public function __construct(SteamUserRepositoryInterface $steamInterface)
	{
		$this->steamInterface = $steamInterface;
		$this->beforeFilter('checkResourcePermission',array('only' => array('index', 'store', 'edit', 'update')));
	}

	/**
	 * Display a listing of the resource.
	 * GET /sessions
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /sessions/create
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
	 * POST /sessions
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
				if( $user = UserImport::fromSteam( substr($openId->identity, -17) ) )
				{
					Auth::login($user);
					if( $user->steam_visibility != 3 ) return Redirect::route('users.show', $user->id);
					return Redirect::to('/');
				}
				else
				{
					return Redirect::route( 'session.login' )->withErrors(new Messagebag(array('Unable to import user from Steam.')));
				}
			}
			else
			{
				return Redirect::route( 'session.login' )->withErrors(new Messagebag(array('Unable to validate OpenID.')));
			}
		}
	}

	/**
	 * Display the specified resource.
	 * GET /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /sessions/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /sessions/{id}
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
