<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Signups\Signup,
	Zeropingheroes\Lanager\Events\Event,
	Zeropingheroes\Lanager\Users\User;
use View, Input, Redirect, Request, Response;

class SignupsController extends BaseController {
	
	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$signups = Signup::with(array('event','user'))->get();

		if ( Request::ajax() ) return Response::json($signups);

		return View::make('signups.index')
					->with('title','Event Signups')
					->with('signups',$signups);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$signup = new Signup;
		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$events = Event::orderBy('name')
						->lists('name','id');

		return View::make('signups.create')
					->with('title','Sign Up User to Event')
					->with('users',$users)
					->with('events',$events)
					->with('signup',$signup);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$signup = new Signup;

		$signup->user_id	= User::findOrFail(Input::get('user_id'))->id;
		$signup->event_id	= Event::findOrFail(Input::get('event_id'))->id;
		
		return $this->process( $signup, 'events.show', 'events.show', array('events' => $signup->event_id) );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$signup = Signup::onlyVisibleUsers()->with(array('event','user'))->findOrFail($id);
		
		if ( Request::ajax() ) return Response::json($signup);
		
		return Redirect::route('signups.index');
	}

	/**
	 * Show the form for editing the specified resource.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$signup = Signup::findOrFail($id);

		if( Input::has('user_id') )	$signup->user_id	= User::findOrFail(Input::get('user_id'))->id;
		if( Input::has('event_id') )$signup->event_id	= Event::findOrFail(Input::get('event_id'))->id;

		return $this->process( $signup );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$signup = Signup::findOrFail($id);

		return $this->process( $signup, 'events.show', 'events.show', array('events' => $signup->event_id)  );
	}

}