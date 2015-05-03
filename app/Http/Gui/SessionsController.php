<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Illuminate\Routing\Controller;
use	Zeropingheroes\Lanager\Domain\Users\User;
use LightOpenID;
use Auth;
use Input;
use Request;
use Redirect;
use View;
use UserImport;
use Notification;
use Cache;
use URL;

class SessionsController extends Controller {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ( Auth::check() ) return Redirect::to('/'); // if user is already logged in, redirect to home

		if ( Input::has('openid_identity') ) return $this->store(); // If request is an OpenID login, store the session

		// Steam OpenID Login URL - cached for 1 day due to request time
		$steamAuthUrl = Cache::remember('steamAuthUrl', 60*24, function()
		{
			$openId = new LightOpenID(Request::server('HTTP_HOST'));
			
			$openId->identity = 'http://steamcommunity.com/openid';
			$openId->returnUrl = URL::route('sessions.create');
			return $openId->authUrl();
		});

		return View::make('sessions.create')
						->with('title','Log In')
						->with('steamAuthUrl', $steamAuthUrl);
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
			return Redirect::route( 'sessions.create' );
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
